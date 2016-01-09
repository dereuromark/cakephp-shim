<?php

namespace Shim\Test\TestCase\Utility;

use Shim\Utility\Session;
use Shim\TestSuite\TestCase;

class SessionTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$_SESSION = [];
	}

	/**
	 * @return void
	 */
	public function testRead() {
		$_SESSION = [
			'Foo' => 'bar'
		];

		$read = Session::read('Baz');
		$this->assertNull($read);

		$read = Session::read('Foo');
		$this->assertSame('bar', $read);
	}

}
