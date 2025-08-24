<?php

namespace Shim\Test\TestCase\Utility;

use Shim\TestSuite\TestCase;
use Shim\Utility\CastTrait;

class CastTraitTest extends TestCase {

	use CastTrait;

	/**
	 * @return void
	 */
	public function testAssertString(): void {
		$result = $this->assertString(null);
		$this->assertNull($result);

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
	public function testCastString(): void {
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
	public function testAssertInt(): void {
		$result = $this->assertInt(null);
		$this->assertNull($result);

		$result = $this->assertInt('');
		$this->assertNull($result);

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
	public function testCastInt(): void {
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
	public function testAssertFloat(): void {
		$result = $this->assertFloat(null);
		$this->assertNull($result);

		$result = $this->assertFloat('');
		$this->assertNull($result);

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
	public function testCastFloat(): void {
		$result = $this->castFloat(null);
		$this->assertSame(0.0, $result);

		$result = $this->castFloat('');
		$this->assertSame(0.0, $result);

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
	public function testAssertBool(): void {
		$result = $this->assertBool(null);
		$this->assertNull($result);

		$result = $this->assertBool('');
		$this->assertNull($result);

		$result = $this->assertBool(false);
		$this->assertFalse($result);

		$result = $this->assertBool('true');
		$this->assertTrue($result);
	}

	/**
	 * @return void
	 */
	public function testCastBool(): void {
		$result = $this->castBool(null);
		$this->assertFalse($result);

		$result = $this->castBool('');
		$this->assertFalse($result);

		$result = $this->castBool(false);
		$this->assertFalse($result);

		$result = $this->castBool(true);
		$this->assertTrue($result);

		$result = $this->castBool('true');
		$this->assertTrue($result);

		$result = $this->castBool('false');
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function testAssertArray(): void {
		$result = $this->assertArray(null);
		$this->assertNull($result);

		$result = $this->assertArray('');
		$this->assertNull($result);

		$result = $this->assertArray([]);
		$this->assertSame([], $result);

		$result = $this->assertArray(['x' => 'y']);
		$this->assertSame(['x' => 'y'], $result);
	}

	/**
	 * @return void
	 */
	public function testCastArray(): void {
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
