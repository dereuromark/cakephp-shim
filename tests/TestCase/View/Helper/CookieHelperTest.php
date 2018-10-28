<?php

namespace Shim\Test\TestCase\View\Helper;

use Cake\Http\ServerRequest;
use Cake\View\View;
use Shim\TestSuite\TestCase;
use Shim\View\Helper\CookieHelper;

class CookieHelperTest extends TestCase {

	/**
	 * @var \Shim\View\Helper\CookieHelper
	 */
	public $Cookie;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->Cookie = new CookieHelper(new View(null));
		$this->Cookie->request = $this->getMockBuilder(ServerRequest::class)->setMethods(['getCookie', 'getCookieParams'])->getMock();
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		unset($this->Table);

		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testGetChookies() {
		$this->Cookie->request->expects($this->at(0))
			->method('getCookieParams')
			->will($this->returnValue(['one' => 1, 'two' => 2]));

		$this->assertSame(['one', 'two'], $this->Cookie->getCookies());
	}

	/**
	 * CookieHelperTest::testCheck()
	 *
	 * @return void
	 */
	public function testCheck() {
		$this->Cookie->request->expects($this->at(0))
			->method('getCookie')
			->will($this->returnValue(null));
		$this->Cookie->request->expects($this->at(1))
			->method('getCookie')
			->will($this->returnValue('val'));

		$this->assertFalse($this->Cookie->check('Foo.key'));
		$this->assertTrue($this->Cookie->check('Foo.key'));
	}

	/**
	 * CookieHelperTest::testRead()
	 *
	 * @return void
	 */
	public function testRead() {
		$this->Cookie->request->expects($this->once())
			->method('getCookie')
			->will($this->returnValue('val'));

		$output = $this->Cookie->read('Foo.key');
		$this->assertTextEquals('val', $output);
	}

}
