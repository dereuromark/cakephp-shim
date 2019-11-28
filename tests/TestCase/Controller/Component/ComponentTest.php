<?php

namespace Shim\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Http\ServerRequest;
use Shim\Controller\Component\Component;
use Shim\TestSuite\TestCase;
use TestApp\Controller\ActionNamesController;

class ComponentTest extends TestCase {

	/**
	 * @var \Cake\Controller\Controller
	 */
	protected $Controller;

	/**
	 * @var \Cake\Controller\ComponentRegistry
	 */
	protected $ComponentRegistry;

	/**
	 * @return void
	 */
	public function setUp() {
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

	/**
	 * @return void
	 */
	public function testAssertValidActionNames() {
		$this->Controller = new ActionNamesController(new ServerRequest());
		$this->ComponentRegistry = new ComponentRegistry($this->Controller);

		$this->deprecated(function() {
			$component = new Component($this->ComponentRegistry);

			$this->assertInstanceOf(ActionNamesController::class, $this->ComponentRegistry->getController());
			$this->assertInstanceOf(ActionNamesController::class, $component->getController());
		});
	}

}
