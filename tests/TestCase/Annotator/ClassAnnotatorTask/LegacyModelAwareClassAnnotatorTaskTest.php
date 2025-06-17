<?php

namespace Shim\Test\TestCase\Annotator\ClassAnnotatorTask;

use Cake\Console\ConsoleIo;
use IdeHelper\Annotator\AbstractAnnotator;
use IdeHelper\Console\Io;
use Shim\Annotator\ClassAnnotatorTask\LegacyModelAwareClassAnnotatorTask;
use Shim\TestSuite\ConsoleOutput;
use Shim\TestSuite\TestCase;

class LegacyModelAwareClassAnnotatorTaskTest extends TestCase {

	protected ConsoleOutput $out;

	protected ConsoleOutput $err;

	protected ?Io $io = null;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->out = new ConsoleOutput();
		$this->err = new ConsoleOutput();
		$consoleIo = new ConsoleIo($this->out, $this->err);
		$this->io = new Io($consoleIo);
	}

	/**
	 * @return void
	 */
	public function testAnnotate(): void {
		$content = file_get_contents(TEST_FILES . 'LegacyModelAwareClassAnnotator' . DS . 'Annotation.missing.php');
		$task = $this->getTask($content);
		$path = '/src/Foo/Foo.php';

		$result = $task->annotate($path);
		$this->assertTrue($result);

		$content = $task->getContent();
		$this->assertTextContains('* @property \Cake\ORM\Table $Notifications', $content);

		$output = $this->out->output();
		$this->assertTextContains('  -> 1 annotation added.', $output);
	}

	/**
	 * @param string $content
	 * @param array $params
	 *
	 * @return \Shim\Annotator\ClassAnnotatorTask\LegacyModelAwareClassAnnotatorTask
	 */
	protected function getTask(string $content, array $params = []): LegacyModelAwareClassAnnotatorTask {
		$params += [
			AbstractAnnotator::CONFIG_DRY_RUN => true,
			AbstractAnnotator::CONFIG_VERBOSE => true,
		];

		return new LegacyModelAwareClassAnnotatorTask($this->io, $params, $content);
	}

}
