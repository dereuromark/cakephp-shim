<?php

namespace Shim\Test\TestCase\TestSuite;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Shim\TestSuite\IntegrationTestCase;

class IntegrationTestCaseTest extends IntegrationTestCase {

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		Router::reload();
		$builder = Router::createRouteBuilder('/');
		$builder->connect('/{controller}/{action}/*');
	}

	/**
	 * @return void
	 */
	public function testDebug(): void {
		$backup = $_SERVER['argv'];

		$_SERVER['argv'] = ['foo'];
		$res = $this->isDebug();
		$this->assertFalse($res);

		$_SERVER['argv'] = ['foo', '--debug'];
		$res = $this->isDebug();
		$this->assertTrue($res);

		$_SERVER['argv'] = $backup;
	}

	/**
	 * A basic GET.
	 *
	 * @return void
	 */
	public function testBasic(): void {
		$this->disableErrorHandlerMiddleware();

		$this->get(['controller' => 'Items', 'action' => 'index']);

		$this->assertResponseCode(200);
		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertNoRedirect();
		$this->assertResponseNotEmpty();
		$this->assertResponseContains('<body>');
		$this->assertResponseContains('My Index Test ctp');
	}

}
