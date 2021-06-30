<?php

namespace Shim\Test\TestCase\Generator\Task;

use Cake\TestSuite\TestCase;
use IdeHelper\Generator\Directive\RegisterArgumentsSet;
use Shim\Generator\Task\ConfigureHelperTask;
use Shim\TestSuite\TestTrait;

class ConfigureHelperTaskTest extends TestCase {

	use TestTrait;

	/**
	 * @var \IdeHelper\Generator\Task\ConfigureHelperTask
	 */
	protected $task;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->task = new ConfigureHelperTask();
	}

	/**
	 * @return void
	 */
	public function testCollect() {
		$result = $this->task->collect();

		$this->assertCount(6, $result);

		/** @var \IdeHelper\Generator\Directive\RegisterArgumentsSet $directive */
		$directive = array_shift($result);
		$this->assertInstanceOf(RegisterArgumentsSet::class, $directive);
		$this->assertSame(ConfigureHelperTask::SET_CONFIGURE_KEYS, $directive->toArray()['set']);

		/** @var \IdeHelper\Generator\Directive\ExpectedArguments $directive */
		$directive = array_shift($result);
		$this->assertSame('\Shim\View\Helper\ConfigureHelper::read()', $directive->toArray()['method']);

		$list = $directive->toArray()['list'];
		$list = array_map(function ($className) {
			return (string)$className;
		}, $list);

		$expected = [
			'argumentsSet(\'configureHelperKeys\')',
		];
		$this->assertSame($expected, $list);
	}

	/**
	 * @return void
	 */
	public function testCollectKeys(): void {
		$result = $this->invokeMethod($this->task, 'collectKeys');

		$this->assertArrayHasKey('App.paths.templates', $result);
		$this->assertArrayNotHasKey('paths', $result);
		$this->assertArrayNotHasKey('templates', $result);

		$this->assertSame('\'debug\'', (string)$result['debug']);
	}

}
