<?php

namespace Shim\Test\TestCase\Utility;

use Shim\TestSuite\TestCase;
use Shim\Utility\Set;

class SetTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function testPushDiff() {
		$result = Set::pushDiff([], []);
		$this->assertSame([], $result);
	}

}
