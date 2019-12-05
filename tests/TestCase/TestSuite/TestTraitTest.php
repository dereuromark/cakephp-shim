<?php

namespace Shim\Test\TestCase\TestSuite;

use Shim\TestSuite\TestCase;

class TestTraitTest extends TestCase {

	/**
	 * @return void
	 */
	public function testOsFix() {
		$result = $this->osFix("Foo\rBar\r\nBaz");
		$this->assertSame("Foo\nBar\nBaz", $result);
	}

	/**
	 * @return void
	 */
	public function testIsVerbose() {
		$result = $this->isVerbose();
		$this->assertTrue(is_bool($result));
	}

	/**
	 * @return void
	 */
	public function testDebug() {
		$this->debug('Foo');
		$this->assertTrue(true);
	}

}
