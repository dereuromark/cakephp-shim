<?php

namespace Shim\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;
use Shim\Controller\Controller;

class ControllerTest extends TestCase {

	/**
	 * @var \Shim\Controller\Controller
	 */
	protected $Controller;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		$this->Controller = new Controller();
		$this->Controller->startupProcess();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Controller);
	}

	/**
	 * @return void
	 */
	public function testDisableCache() {
		$this->Controller->disableCache();

		$result = $this->Controller->getResponse()->getHeaders();
		$expected = ['Content-Type', 'Pragma', 'Expires', 'Last-Modified', 'Cache-Control'];
		$this->assertSame($expected, array_keys($result));
	}

	/**
	 * @return void
	 */
	public function testAfterFilter() {
		$event = new Event('afterFilter');

		$this->Controller->afterFilter($event);
		$headers = $this->Controller->getResponse()->getHeaders();
		$this->assertNotEmpty($headers['Content-Type']);
	}

}
