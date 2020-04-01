<?php

namespace Shim\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\App;
use Cake\Filesystem\Folder;
use IdeHelper\Utility\AppPath;

/**
 * See docs on Json shim type class.
 *
 * This command will not be ported to 4.x. Fix your `json` type columns now in 3.x.
 */
class ShimJsonCommand extends Command {

	/**
	 * @var bool
	 */
	public $modelClass = false;

	/**
	 * @var \Cake\Console\ConsoleIo
	 */
	protected $io;

	/**
	 * E.g.:
	 * bin/cake branch_aliases spryker/spryker
	 * or
	 * bin/cake branch_aliases spryker/spryker-shop bugfix/foo-bar
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 *
	 * @return int|null The exit code or null for success
	 */
	public function execute(Arguments $args, ConsoleIo $io) {
		$plugin = $args->getArgument('plugin');
		$model = $args->getArgument('model');
		$dryRun = (bool)$args->getOption('dry-run');

		$this->io = $io;

		$models = $this->getModels($plugin, $model);

		return $this->fix($models, $dryRun);
	}

	/**
	 * @param array $models
	 * @param bool $dryRun
	 *
	 * @return int
	 */
	protected function fix($models, $dryRun) {
		$return = static::CODE_SUCCESS;

		foreach ($models as $model) {
			$table = $this->getTableLocator()->get($model);
			$columns = $table->getSchema()->columns();
			foreach ($columns as $column) {
				$field = $table->getSchema()->getColumn($column);
				if ($field['type'] !== 'json') {
					continue;
				}

				$fieldName = $model . ' `' . $column . '`';
				$this->io->verbose($fieldName);

				if ($field['null'] !== true) {
					$this->io->err($fieldName . ': expected DEFAULT NULL, but NOT NULL');
					$return = static::CODE_ERROR;
				}
				$count = $table->find()->where([$column => 'null'])->count();
				if (!$count) {
					continue;
				}

				$this->io->warning($fieldName . ': ' . $count . ' entries to fix');
				if (!$dryRun) {
					$fixed = $table->updateAll([$column => null], [$column => 'null']);
					$this->io->success($fieldName . ': ' . $fixed . ' entries fixed');
				} else {
					$return = static::CODE_ERROR;
				}
			}
		}

		return $return;
	}

	/**
	 * @param string|null $plugin
	 * @param string|null $model
	 *
	 * @return array
	 */
	protected function getModels($plugin, $model) {
		$models = [];
		if ($model) {
			if ($plugin && strpos($model, '.') === false) {
				$model = $plugin . '.' . $model;
			}
			return [$model => $model];
		}

		$folders = AppPath::get('Model/Table', $plugin);
		foreach ($folders as $folder) {
			$models = $this->addModels($models, $folder);
		}

		return $models;
	}

	/**
	 * @param array $models
	 * @param string $folder
	 * @param string|null $plugin
	 *
	 * @return string[]
	 */
	protected function addModels(array $models, $folder, $plugin = null) {
		$folderContent = (new Folder($folder))->read(Folder::SORT_NAME, true);

		foreach ($folderContent[1] as $file) {
			preg_match('/^(.+)Table\.php$/', $file, $matches);
			if (!$matches) {
				continue;
			}
			$model = $matches[1];
			if ($plugin) {
				$model = $plugin . '.' . $model;
			}

			$className = App::className($model, 'Model/Table', 'Table');
			if (!$className) {
				continue;
			}

			$models[$model] = $model;
		}

		return $models;
	}

	/**
	 * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
	 *
	 * @return \Cake\Console\ConsoleOptionParser The built parser.
	 */
	protected function buildOptionParser(ConsoleOptionParser $parser) {
		$parser = parent::buildOptionParser($parser);
		$parser->setDescription('Fix the `null` string to a true NULL value for JSON columns. This is a 4.x forward shim type available.');

		$parser->addArgument('model', [
			'help' => 'Model (Table) name. If empty, runs for all found.',
			'required' => false,
		]);
		$parser->addOption('plugin', [
			'help' => 'Plugin to run this for.',
		]);

		$parser->addOption('dry-run', [
			'help' => 'Dry-run the command.',
			'short' => 'd',
			'boolean' => true,
		]);

		return $parser;
	}

}
