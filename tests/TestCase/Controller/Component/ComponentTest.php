<?php
namespace Shim\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Http\ServerRequest;
use Shim\Controller\Component\Component;
use Shim\TestSuite\TestCase;

class ComponentTest extends TestCase {

	/**
	 * @var \Cake\Controller\Controller
	 */
	public $Controller;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->Controller = new Controller(new ServerRequest());
		$this->ComponentRegistry = new ComponentRegistry($this->Controller);
	}

	/**
	 * testBeforeFilter method
	 *
	 * @return void
	 */
	public function testBeforeFilter() {
		$Component = new Component($this->ComponentRegistry);

		$this->assertInstanceOf(Controller::class, $Component->Controller);
	}

}
