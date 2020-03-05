<?php

namespace Shim\Test\TestCase\TestSuite;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Shim\TestSuite\IntegrationTestCase;

class IntegrationTestCaseTest extends IntegrationTestCase {

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		Router::connect('/:controller/:action/*');
	}

	/**
	 * @return void
	 */
	public function testDebug() {
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
	public function testBasic() {
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
