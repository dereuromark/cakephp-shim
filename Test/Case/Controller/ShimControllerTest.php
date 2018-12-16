<?php
Configure::write('debug', 2);
App::uses('ShimController', 'Shim.Controller');
App::uses('ComponentCollection', 'Controller');

class ShimControllerTest extends ControllerTestCase {

	public $fixtures = ['core.comment'];

	public $ShimController;

	public function setUp() {
		parent::setUp();

		$this->ShimController = new TestShimController(new CakeRequest(), new CakeResponse());
		$this->ShimController->constructClasses();
		$this->ShimController->startupProcess();
	}

	public function tearDown() {
		Configure::delete('Shim');
		parent::tearDown();
	}

	/**
	 * ShimControllerTest::testObject()
	 *
	 * @return void
	 */
	public function testObject() {
		$this->assertTrue(is_object($this->ShimController));
		$this->assertInstanceOf('ShimController', $this->ShimController);
	}

	/**
	 * ShimControllerTest::testGo()
	 *
	 * @return void
	 */
	public function testDisableCache() {
		$this->ShimController->disableCache();

		$header = $this->ShimController->response->header();
		$expected = ['Pragma', 'Expires', 'Last-Modified', 'Cache-Control'];
		$this->assertSame($expected, array_keys($header));
	}

	/**
	 * ShimControllerTest::testPaginate()
	 *
	 * @return void
	 */
	public function testPaginate() {
		Configure::write('Paginator.paramType', 'querystring');

		$this->ShimController->paginate = [
			'limit' => 2,
		] + $this->ShimController->paginate;

		$result = $this->ShimController->paginate();
		$this->assertNotEmpty($result);
	}

	/**
	 * ShimControllerTest::testPaginate()
	 *
	 * @return void
	 */
	public function testPaginatePage3() {
		Configure::write('Paginator.paramType', 'querystring');

		$this->ShimController = new TestShimController(new CakeRequest('/foo/bar?page=3'), new CakeResponse());
		$this->ShimController->constructClasses();
		$this->ShimController->startupProcess();

		$this->ShimController->paginate = [
			'limit' => 2,
		] + $this->ShimController->paginate;

		$result = $this->ShimController->paginate();
		$this->assertNotEmpty($result);
		$this->assertSame(['5', '6'], Hash::extract($result, '{n}.Comment.id'));
	}

	/**
	 * @return void
	 */
	public function testLoadComponent() {
		$is = $this->ShimController->Components->loaded('Security');
		$this->assertFalse($is);

		$is = $this->ShimController->loadComponent('Security');
		$this->assertInstanceOf('SecurityComponent', $is);

		$is = $this->ShimController->Components->loaded('Security');
		$this->assertTrue($is);
	}

	/**
	* @expectedException PHPUNIT_FRAMEWORK_ERROR_DEPRECATED
	* @expectedExceptionMessage Property base is deprecated. Use CakeRequest::$base instead.
	* @return void
	*/
	public function testBasePropery() {
		Configure::write(Shim::CONTROLLER_BASE, true);
		$this->ShimController->base;
	}

}

class TestShimController extends ShimController {

	public $uses = ['Comment'];

}
