<?php

namespace Shim\Test\TestCase\Utility;

use Shim\TestSuite\TestCase;
use Shim\Utility\Inflector;

class InflectorTest extends TestCase {

	/**
	 * @return void
	 */
	public function testPluralize() {
		$string = 'my_index';
		$result = Inflector::pluralize($string);
		$this->assertSame('my_indexes', $result);

		$string = 'myIndex';
		$result = Inflector::pluralize($string);
		$this->assertSame('myIndexes', $result);
	}

}
