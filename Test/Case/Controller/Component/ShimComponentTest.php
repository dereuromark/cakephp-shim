<?php

App::uses('ShimComponent', 'Shim.Controller/Component');
App::uses('ShimController', 'Shim.Controller');
App::uses('ShimTestCase', 'Shim.TestSuite');

class ShimComponentTest extends ShimTestCase {

	public $ShimController;

	public function setUp() {
		parent::setUp();

		App::build(array(
			'Controller' => array(CakePlugin::path('Shim') . 'Test' . DS . 'test_app' . DS . 'Controller' . DS),
			'Model' => array(CakePlugin::path('Shim') . 'Test' . DS . 'test_app' . DS . 'Model' . DS),
			'View' => array(CakePlugin::path('Shim') . 'Test' . DS . 'test_app' . DS . 'View' . DS)
		), App::RESET);

		$this->request = $this->getMock('CakeRequest', ['referer']);
		$this->ShimController = new TestShimComponentController($this->request, new CakeResponse());
		$this->ShimController->constructClasses();
		$this->ShimController->startupProcess();
	}

	public function tearDown() {
		parent::tearDown();

  	unset($this->ShimController);
  	Configure::delete('App.warnAboutNamedParams');
	}

	/**
	 * testUrlQueryStrings()
	 *
	 * @return void
	 */
	public function testUrlQueryStrings() {
		Configure::write('App.warnAboutNamedParams', true);

		$this->ShimController = new TestShimComponentController(new CakeRequest('/foo/bar?page=3'), new CakeResponse());
		$this->ShimController->constructClasses();
		$this->ShimController->startupProcess();
	}

	/**
	 * testUrlQueryStrings()
	 *
	 * @expectedException CakeException
	 * @return void
	 */
	public function testUrlNamedParams() {
		Configure::write('App.warnAboutNamedParams', true);

		$referer = '/foobar';
		$this->request->expects($this->once())
			->method('referer')
			->with(true)
			->will($this->returnValue($referer));

		$this->ShimController = new TestShimComponentController($this->request, new CakeResponse());
		$this->ShimController->request->params['named'] = ['x' => 'y'];

		$this->ShimController->constructClasses();
		$this->ShimController->startupProcess();
	}

}

class TestShimComponentController extends ShimController {

	public $uses = [];

	public $components = ['Shim.Shim'];

}
