<?php
namespace Shim\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Shim\Controller\Controller;

/**
 */
class ControllerTest extends TestCase {

	/**
	 * @var \Shim\Controller\Controller
	 */
	public $Controller;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		$this->Controller = new Controller();
		$this->Controller->startupProcess();
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Controller);
	}

	/**
	 * @return void
	 */
	public function testDisableCache() {
		$this->Controller->disableCache();

		$result = $this->Controller->response->getHeaders();
		$expected = ['Content-Type', 'Pragma', 'Expires', 'Last-Modified', 'Cache-Control'];
		$this->assertSame($expected, array_keys($result));
	}

	/**
	 * @return void
	 */
	public function testBeforeRender() {
		$event = new Event('beforeRender');

		$this->Controller->request->data = new Entity();

		$this->Controller->beforeRender($event);

		$this->assertSame([], $this->Controller->request->getData());
	}

	/**
	 * @return void
	 */
	public function testAfterFilter() {
		$event = new Event('afterFilter');

		$this->Controller->afterFilter($event);
	}

}
