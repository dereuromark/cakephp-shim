<?php

namespace Shim\Test\TestCase\Utility;

use Shim\TestSuite\TestCase;
use Shim\Utility\CastTrait;
use Shim\Utility\Inflector;

class InflectorTest extends TestCase {

	use CastTrait;

	/**
	 * @return void
	 */
	public function testSlug() {
		$result = Inflector::slug('äöü');
		$expected = 'aeoeue';
		$this->assertSame($expected, $result);
	}

}
