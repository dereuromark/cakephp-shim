<?php

namespace Shim\Test\TestCase\Utility;

use Shim\TestSuite\TestCase;
use Shim\Utility\Multibyte;

class MultibyteTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	public function testUtf() {
		$string = 'äöü';
		$result = Multibyte::utf8($string);

		$expected = [
			228,
			246,
			252
		];
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testAscii() {
		$array = [
			228,
			246,
			252
		];
		$result = Multibyte::ascii($array);

		$this->assertSame('äöü', $result);
	}

}
