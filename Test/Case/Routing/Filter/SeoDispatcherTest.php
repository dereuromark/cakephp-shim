<?php
App::uses('SeoDispatcher', 'Shim.Routing/Filter');
App::uses('CakeEvent', 'Event');
App::uses('CakeResponse', 'Network');
App::uses('Dispatcher', 'Routing');

/**
 * Class SeoDispatcherTest
 */
class SeoDispatcherTest extends CakeTestCase {

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
		Configure::write('Dispatcher.filters', []);
		Configure::write('Routing.prefixes', []);
	}

	/**
	 * test that Seo filters work
	 *
	 * @return void
	 */
	public function testSeoFilter() {
		$filter = new SeoDispatcher();

		$response = $this->getMock('CakeResponse', ['_sendHeader']);
		$Dispatcher = new Dispatcher();
		$request = new CakeRequest('controller_name/action_name');
		$request->query = [];
		$event = new CakeEvent('DispatcherTest', $Dispatcher, compact('request', 'response'));
		$Dispatcher->parseParams($event);
		$this->assertNull($filter->beforeDispatch($event));
		$this->assertFalse($event->isStopped());

		$response = $this->getMock('CakeResponse', ['_sendHeader']);
		$Dispatcher = new Dispatcher();
		$request = new CakeRequest('controllerName/action_name');
		$request->query = ['x' => 'y'];
		$event = new CakeEvent('DispatcherTest', $Dispatcher, compact('request', 'response'));
		$Dispatcher->parseParams($event);
		$this->assertSame($response, $filter->beforeDispatch($event));
		$this->assertTrue($event->isStopped());

		Configure::write('Routing.prefixes', ['admin']);
		$response = $this->getMock('CakeResponse', ['_sendHeader']);
		$Dispatcher = new Dispatcher();
		$request = new CakeRequest('Admin/Shim/ControllerName/ActionName');
		$request->query = ['x' => 'y'];
		$event = new CakeEvent('DispatcherTest', $Dispatcher, compact('request', 'response'));
		$Dispatcher->parseParams($event);
		$this->assertSame($response, $filter->beforeDispatch($event));
		$this->assertTrue($event->isStopped());

		$this->assertSame(302, $response->statusCode());
		$header = $response->header();
		// Currently only recognizes the first two in tests (not controller or action yet)
		$expected = '/admin/shim/ControllerName/ActionName?x=y';
		$this->assertContains($expected, $header['Location']);
	}

}
