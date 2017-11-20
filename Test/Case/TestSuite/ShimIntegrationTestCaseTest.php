<?php
App::uses('ShimIntegrationTestCase', 'Shim.TestSuite');
App::uses('ShimTestCase', 'Shim.TestSuite');

class ShimIntegrationTestCaseTest extends ShimIntegrationTestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'core.session',
	];

	public function setUp() {
		parent::setUp();

		App::build([
			'Controller' => [CakePlugin::path('Shim') . 'Test' . DS . 'test_app' . DS . 'Controller' . DS],
			'Model' => [CakePlugin::path('Shim') . 'Test' . DS . 'test_app' . DS . 'Model' . DS],
			'View' => [CakePlugin::path('Shim') . 'Test' . DS . 'test_app' . DS . 'View' . DS]
		], App::RESET);
	}

	/**
	 * A basic GET.
	 *
	 * @return void
	 */
	public function testBasic() {
		$this->get(['controller' => 'items', 'action' => 'index']);
		$this->assertResponseCode(200);
		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertNoRedirect();
		$this->assertResponseNotEmpty();
		$this->assertResponseContains('My Index Test ctp');
	}

	/**
	 * Test that POST also works.
	 *
	 * @return void
	 */
	public function testPosting() {
		$data = [
			'key' => 'sth'
		];
		$this->post(['controller' => 'items', 'action' => 'posting'], $data);
		$this->assertResponseCode(200);
		$this->assertNoRedirect();
	}

	/**
	 * Let us change a value in session
	 *
	 * @return void
	 */
	public function testSession() {
		$this->session(['Auth.User.id' => 1]);

		$this->get(['controller' => 'items', 'action' => 'session']);
		$this->assertResponseCode(200);
		$this->assertNoRedirect();

		$this->assertSession('2', 'Auth.User.id');
	}

	/**
	 * Redirecting is recognized as 302 status code.
	 *
	 * @return void
	 */
	public function testRedirecting() {
		Configure::write('App.baseUrl', '/');

		$this->get(['controller' => 'items', 'action' => 'redirecting']);
		$this->assertResponseCode(302);
		$this->assertRedirect('/foobar');

		$this->assertSession('yeah', 'Message.flash.message');

		// Make sure we dont have cross contamination from the previous test
		$this->assertSession(null, 'Auth.User.id');
		$this->assertResponseEmpty();
		$this->assertResponseSuccess();
	}

	/**
	 * We still have to set assertion headers, though, for exceptions.
	 *
	 * @expectedException NotFoundException
	 * @return void
	 */
	public function testExceptional() {
		$this->get(['controller' => 'items', 'action' => 'exceptional']);
	}

}
