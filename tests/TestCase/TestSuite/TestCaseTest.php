<?php

namespace Shim\Test\TestCase\TestSuite;

use Shim\TestSuite\TestCase;

class TestCaseTest extends TestCase {

	/**
	 * test testAssertWithinRange()
	 *
	 * @return void
	 */
	public function testAssertWithinRange(): void {
		$this->assertWithinRange(21, 22, 1, 'Not within range');
		$this->assertWithinRange(21.3, 22.2, 1.0, 'Not within range');
	}

	/**
	 * test testAssertNotWithinRange()
	 *
	 * @return void
	 */
	public function testAssertNotWithinRange(): void {
		$this->assertNotWithinRange(21, 23, 1, 'Within range');
		$this->assertNotWithinRange(21.3, 22.2, 0.7, 'Within range');
	}

}
