<?php
namespace Shim\Test\TestCase\TestSuite;

use Shim\TestSuite\TestCase;

class TestTraitTest extends TestCase {

	public function setUp() {
		parent::setUp();
	}

	/**
	 * test testAssertWithinRange()
	 *
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
		$this->isVerbose();
	}

	/**
	 * @return void
	 */
	public function testDebug() {
		$this->debug('Foo');
	}

}
