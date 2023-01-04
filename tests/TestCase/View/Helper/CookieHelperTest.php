<?php

namespace Shim\Test\TestCase\View\Helper;

use Cake\Http\ServerRequest;
use Cake\View\View;
use PHPUnit\Framework\MockObject\MockObject;
use Shim\TestSuite\TestCase;
use Shim\View\Helper\CookieHelper;

class CookieHelperTest extends TestCase {

	protected CookieHelper $Cookie;

	protected ServerRequest|MockObject $request;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		/** @var \Cake\Http\ServerRequest|\PHPUnit\Framework\MockObject\MockObject $request */
		$this->request = $this->getMockBuilder(ServerRequest::class)->setMethods(['getCookie', 'getCookieParams'])->getMock();
		$this->Cookie = new CookieHelper(new View($this->request));
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		unset($this->Table);

		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testGetChookies(): void {
		$this->request->expects($this->at(0))
			->method('getCookieParams')
			->will($this->returnValue(['one' => 1, 'two' => 2]));

		$this->assertSame(['one', 'two'], $this->Cookie->getCookies());
	}

	/**
	 * CookieHelperTest::testCheck()
	 *
	 * @return void
	 */
	public function testCheck(): void {
		$this->request->expects($this->at(0))
			->method('getCookie')
			->will($this->returnValue(null));
		$this->request->expects($this->at(1))
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
	public function testRead(): void {
		$this->request->expects($this->once())
			->method('getCookie')
			->will($this->returnValue('val'));

		$output = $this->Cookie->read('Foo.key');
		$this->assertTextEquals('val', $output);
	}

}
