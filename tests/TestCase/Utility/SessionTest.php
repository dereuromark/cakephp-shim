<?php

namespace Shim\Test\TestCase\Utility;

use Cake\Core\Configure;
use Shim\TestSuite\TestCase;
use Shim\Utility\Session;

class SessionTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->skipIf(version_compare(PHP_VERSION, '7.2', '>='), 'Deprecated and does not work for PHP7.2+');

		$_SESSION = [];
	}

	/**
	 * @return void
	 */
	public function testId() {
		$_SESSION = [
			'Foo' => 'bar'
		];

		$result = Session::started();
		$this->assertFalse($result);

		Session::id('foo');

		$result = Session::started();
		$this->assertFalse($result);

		$result = Session::id();
		$this->assertSame('foo', $result);
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

	/**
	 * @return void
	 */
	public function testCheck() {
		$_SESSION = [
			'Foo' => 'bar'
		];

		$result = Session::check('Foo');
		$this->assertTrue($result);

		$result = Session::check('Baz');
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function _testValid() {
		Configure::write('Session.checkAgent', false);
		$_SESSION = [
			'Foo' => 'bar',
			'Config' => [
				'time' => time() + DAY,
				'userAgent' => 'xyz',
			]
		];

		$result = Session::valid();
		$this->assertTrue($result);
	}

}
