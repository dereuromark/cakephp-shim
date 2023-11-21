<?php
declare(strict_types=1);

namespace Shim\Datasource;

use Cake\Datasource\Exception\MissingModelException;
use Cake\Datasource\FactoryLocator;
use Cake\Datasource\Locator\LocatorInterface;
use Cake\Datasource\RepositoryInterface;
use RuntimeException;
use UnexpectedValueException;
use function Cake\Core\pluginSplit;

/**
 * Provides functionality for loading table classes
 * and other repositories onto properties of the host object.
 *
 * Example users of this trait are {@link \Cake\Controller\Controller} and
 * {@link \Cake\Command\Command}.
 *
 * Note: Make sure to use {@link \Cake\Datasource\ModelAwareTrait} and
 * attribute #[\AllowDynamicProperties] on your classes that use this to avoid this blowing
 * up in PHP 8.2+.
 *
 * @mixin \Cake\Datasource\ModelAwareTrait
 */
trait LegacyModelAwareTrait {

	/**
	 * Fetch or construct a model instance from a locator.
	 *
	 * Uses a modelFactory based on `$modelType` to fetch and construct a `RepositoryInterface`
	 * and return it. The default `modelType` can be defined with `setModelType()`.
	 *
	 * Unlike `loadModel()` this method will *not* set an object property.
	 *
	 * If a repository provider does not return an object a MissingModelException will
	 * be thrown.
	 *
	 * @param string|null $modelClass Name of model class to load. Defaults to $this->modelClass.
	 *  The name can be an alias like `'Post'` or FQCN like `App\Model\Table\PostsTable::class`.
	 * @param string|null $modelType The type of repository to load. Defaults to the getModelType() value.
	 * @throws \Cake\Datasource\Exception\MissingModelException If the model class cannot be found.
	 * @throws \UnexpectedValueException If $modelClass argument is not provided
	 *   and ModelAwareTrait::$modelClass property value is empty.
	 * @return \Cake\Datasource\RepositoryInterface The model instance created.
	 */
	public function loadModel(?string $modelClass = null, ?string $modelType = null): RepositoryInterface {
		$modelClass ??= $this->detectDefaultModelClass();
		if (!$modelClass) {
			throw new UnexpectedValueException('Default modelClass is empty');
		}
		$modelType ??= $this->getModelType();

		$options = [];
		if (strpos($modelClass, '\\') === false) {
			[, $alias] = pluginSplit($modelClass, true);
		} else {
			$options['className'] = $modelClass;
			/** @psalm-suppress PossiblyFalseOperand */
			$alias = substr(
				$modelClass,
				strrpos($modelClass, '\\') + 1,
				-strlen($modelType),
			);
			$modelClass = $alias;
		}

		$factory = $this->_modelFactories[$modelType] ?? FactoryLocator::get($modelType);
		if ($factory instanceof LocatorInterface) {
			$instance = $factory->get($modelClass, $options);
		} else {
			$instance = $factory($modelClass, $options);
		}

		$this->{$alias} = $instance;
		if ($instance) {
			return $instance;
		}

		throw new MissingModelException([$modelClass, $modelType]);
	}

	/**
	 * @return string
	 */
	protected function detectDefaultModelClass(): string {
		$defaultModel = $this->modelClass;
		if (isset($this->defaultTable)) {
			$defaultModel = $this->defaultTable;
		} elseif (isset($this->defaultModel)) {
			$defaultModel = $this->defaultModel;
		}

		if ($defaultModel === null) {
			throw new RuntimeException('Cannot detect default model, please specify `$defaultTable` or `$modelClass`.');
		}

		return $defaultModel;
	}

}
