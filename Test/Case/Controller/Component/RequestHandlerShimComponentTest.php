<?php

App::uses('RequestHandlerShimComponent', 'Shim.Controller/Component');
App::uses('ComponentCollection', 'Controller');
App::uses('Controller', 'Controller');
App::uses('CakeTestCase', 'TestSuite');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');

/**
 * SidebarComponent test case
 */
class RequestHandlerShimComponentTest extends CakeTestCase {

	public $RequestHandlerComponent;

	public function setUp() {
		parent::setUp();

		$collection = new ComponentCollection();
		$controller = new Controller(new CakeRequest(), new CakeResponse());
		$collection->init($controller);
		$this->RequestHandlerComponent = new RequestHandlerShimComponent($collection);
		$this->RequestHandlerComponent->initialize($controller);
		$this->RequestHandlerComponent->startup($controller);

		$_SERVER['HTTP_USER_AGENT'] = '';

		$this->skipIf(!class_exists('Detection\\MobileDetect'), 'Please install MobileDetect for these tests to run.');
	}

	/**
	 * @return void
	 */
	public function testDetector() {
		$result = $this->RequestHandlerComponent->detector();
		$this->assertInstanceOf('Detection\\MobileDetect', $result);
	}

	/**
	 * @return void
	 */
	public function testMobileDesktop() {
		$result = $this->RequestHandlerComponent->isMobile();
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function testMobile() {
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla\/5.0 (Linux; Android 4.4.3; HTC One_M8 Build\/KTU84L) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/36.0.1985.135 Mobile Safari\/537.36';

		$result = $this->RequestHandlerComponent->isMobile();
		$this->assertTrue($result);

		// Mobile are not also tablet
		$result = $this->RequestHandlerComponent->isTablet();
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function testMobileWithCore() {
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla\/5.0 (Linux; Android 4.4.3; HTC One_M8 Build\/KTU84L) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/36.0.1985.135 Mobile Safari\/537.36';

		$this->RequestHandlerComponent->settings['includeCore'] = true;

		$result = $this->RequestHandlerComponent->isMobile();
		$this->assertTrue($result);

		// Mobile are not also tablet
		$result = $this->RequestHandlerComponent->isTablet();
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function testTabletDesktop() {
		$result = $this->RequestHandlerComponent->isTablet();
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function testTablet() {
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla\/5.0 (Linux; Android 4.2.2; A3-A11 Build\/JDQ39) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/34.0.1847.114 Safari\/537.36';

		$result = $this->RequestHandlerComponent->isTablet();
		$this->assertTrue($result);

		// Tablets are also mobile
		$result = $this->RequestHandlerComponent->isMobile();
		$this->assertTrue($result);
	}

}
