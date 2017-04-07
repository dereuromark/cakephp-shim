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

		$array1 = ['ModelOne' => ['id' => 1001, 'field_one' => 'a1.m1.f1', 'field_two' => 'a1.m1.f2']];
		$array2 = ['ModelOne' => ['id' => 1003, 'field_one' => 'a3.m1.f1', 'field_two' => 'a3.m1.f2', 'field_three' => 'a3.m1.f3']];
		$result = Set::pushDiff($array1, $array2);

		$expected = [
			'ModelOne' => [
				'id' => 1001,
				'field_one' => 'a1.m1.f1',
				'field_two' => 'a1.m1.f2',
				'field_three' => 'a3.m1.f3',
			],
		];
		$this->assertSame($expected, $result);
	}

}
