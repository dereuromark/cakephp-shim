<?php
namespace Shim\Test\TestCase\Controller;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component\CommonComponent;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Session;
use Cake\TestSuite\TestCase;
use Shim\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\Entity;

/**
 */
class ControllerTest extends TestCase {

	public $Controller;

	public function setUp() {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		$this->Controller = new Controller();
		$this->Controller->startupProcess();
	}

	public function tearDown() {
		parent::tearDown();

		unset($this->Controller);
	}

	/**
	 * CommonComponentTest::testLoadComponent()
	 *
	 * @return void
	 */
	public function testDisableCache() {
		$this->Controller->disableCache();

		$result = $this->Controller->response->header();
		$expected = ['Pragma', 'Expires', 'Last-Modified', 'Cache-Control'];
		$this->assertSame($expected, array_keys($result));
	}

	/**
	 * @return void
	 */
	public function testBeforeRender() {
		$event = new Event('beforeRender');

		$this->Controller->request->data = new Entity();

		$this->Controller->beforeRender($event);

		$this->assertSame([], $this->Controller->request->data);
	}

	/**
	 * @return void
	 */
	public function testAfterFilter() {
		$event = new Event('afterFilter');

		$this->Controller->afterFilter($event);
	}

}
