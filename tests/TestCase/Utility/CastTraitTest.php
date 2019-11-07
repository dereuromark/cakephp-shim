<?php

namespace Shim\Test\TestCase\Utility;

use Shim\TestSuite\TestCase;
use Shim\Utility\CastTrait;

class CastTraitTest extends TestCase {

	use CastTrait;

	/**
	 * @return void
	 */
	public function testAssertString() {
		$result = $this->assertString(null);
		$this->assertSame(null, $result);

		$result = $this->assertString('');
		$this->assertSame('', $result);

		$result = $this->assertString(false);
		$this->assertSame('', $result);

		$result = $this->assertString('0');
		$this->assertSame('0', $result);
	}

	/**
	 * @return void
	 */
	public function testCastString() {
		$result = $this->castString(null);
		$this->assertSame('', $result);

		$result = $this->castString('');
		$this->assertSame('', $result);

		$result = $this->castString(false);
		$this->assertSame('', $result);

		$result = $this->castString('0');
		$this->assertSame('0', $result);
	}

	/**
	 * @return void
	 */
	public function testAssertInt() {
		$result = $this->assertInt(null);
		$this->assertSame(null, $result);

		$result = $this->assertInt('');
		$this->assertSame(null, $result);

		$result = $this->assertInt(-3);
		$this->assertSame(-3, $result);

		$result = $this->assertInt(2.0);
		$this->assertSame(2, $result);

		$result = $this->assertInt('0');
		$this->assertSame(0, $result);
	}

	/**
	 * @return void
	 */
	public function testCastInt() {
		$result = $this->castInt(null);
		$this->assertSame(0, $result);

		$result = $this->castInt('');
		$this->assertSame(0, $result);

		$result = $this->castInt(-2.0);
		$this->assertSame(-2, $result);

		$result = $this->castInt('0');
		$this->assertSame(0, $result);

		$result = $this->castInt('3.0');
		$this->assertSame(3, $result);
	}

	/**
	 * @return void
	 */
	public function testAssertFloat() {
		$result = $this->assertFloat(null);
		$this->assertSame(null, $result);

		$result = $this->assertFloat('');
		$this->assertSame(null, $result);

		$result = $this->assertFloat(-3);
		$this->assertSame(-3.0, $result);

		$result = $this->assertFloat(2.0);
		$this->assertSame(2.0, $result);

		$result = $this->assertFloat('0');
		$this->assertSame(0.0, $result);
	}

	/**
	 * @return void
	 */
	public function testCastFloat() {
		$result = $this->castFloat(null);
		$this->assertSame(0, $result);

		$result = $this->castFloat('');
		$this->assertSame(0, $result);

		$result = $this->castFloat(-2.0);
		$this->assertSame(-2.0, $result);

		$result = $this->castFloat('0');
		$this->assertSame(0.0, $result);

		$result = $this->castFloat('3.0');
		$this->assertSame(3.0, $result);
	}

	/**
	 * @return void
	 */
	public function testAssertBool() {
		$result = $this->assertBool(null);
		$this->assertSame(null, $result);

		$result = $this->assertBool('');
		$this->assertSame(null, $result);

		$result = $this->assertBool(false);
		$this->assertSame(false, $result);

		$result = $this->assertBool('true');
		$this->assertSame(true, $result);
	}

	/**
	 * @return void
	 */
	public function testCastBool() {
		$result = $this->castBool(null);
		$this->assertSame(false, $result);

		$result = $this->castBool('');
		$this->assertSame(false, $result);

		$result = $this->castBool(false);
		$this->assertSame(false, $result);

		$result = $this->castBool(true);
		$this->assertSame(true, $result);

		$result = $this->castBool('true');
		$this->assertSame(true, $result);

		$result = $this->castBool('false');
		$this->assertSame(false, $result);
	}

	/**
	 * @return void
	 */
	public function testAssertArray() {
		$result = $this->assertArray(null);
		$this->assertSame(null, $result);

		$result = $this->assertArray('');
		$this->assertSame(null, $result);

		$result = $this->assertArray([]);
		$this->assertSame([], $result);

		$result = $this->assertArray(['x' => 'y']);
		$this->assertSame(['x' => 'y'], $result);
	}

	/**
	 * @return void
	 */
	public function testCastArray() {
		$result = $this->castArray(null);
		$this->assertSame([], $result);

		$result = $this->castArray('');
		$this->assertSame([], $result);

		$result = $this->castArray([]);
		$this->assertSame([], $result);

		$result = $this->castArray(['x' => 'y']);
		$this->assertSame(['x' => 'y'], $result);
	}

}
