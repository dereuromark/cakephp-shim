<?php
namespace Shim\Test\TestCase\TestSuite;

use Shim\TestSuite\IntegrationTestCase;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Routing\DispatcherFactory;

class IntegrationTestCaseTest extends IntegrationTestCase {

	public function setUp() {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		Router::connect('/:controller/:action/*', [], ['routeClass' => 'InflectedRoute']);
		DispatcherFactory::clear();
		DispatcherFactory::add('Routing');
		DispatcherFactory::add('ControllerFactory');
	}

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
	 * FIXME
	 *
	 * @return void
	 */
	public function _testBasic() {
		//$this->get('/items/index');
		$this->get(['controller' => 'Items', 'action' => 'index']);

		debug($this->_response->body());
		file_put_contents(TMP . 'x.html', $this->_response->body());
		die();
		$this->assertResponseCode(200);
		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertNoRedirect();
		$this->assertResponseNotEmpty();
		$this->assertResponseContains('My Index Test ctp');
	}

}
