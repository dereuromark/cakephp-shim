<?php

namespace Shim\Test\TestCase\TestSuite;

use Shim\TestSuite\TestCase;
use TestApp\Model\Table\UuidItemsTable;
use TestApp\Model\Table\WheelsTable;

class TestTraitTest extends TestCase {

	/**
	 * @return void
	 */
	public function testOsFix(): void {
		$result = $this->osFix("Foo\rBar\r\nBaz");
		$this->assertSame("Foo\nBar\nBaz", $result);
	}

	/**
	 * @return void
	 */
	public function testDebug(): void {
		$_SERVER['argv'] = ['-v'];

		ob_start();
		$this->debug('Foo');
		$result = ob_get_clean();
		$this->assertTextContains('Foo', $result);
	}

	/**
	 * @return void
	 */
	public function testIsDebug(): void {
		$result = $this->isDebug();
		$this->assertFalse($result);

		$_SERVER['argv'] = ['--debug'];
		$result = $this->isDebug();
		$this->assertTrue($result);
	}

	/**
	 * @return void
	 */
	public function testIsVerbose(): void {
		$_SERVER['argv'] = ['--debug'];
		$result = $this->isVerbose();
		$this->assertFalse($result);

		$_SERVER['argv'] = ['-v'];
		$result = $this->isVerbose();
		$this->assertTrue($result);

		$_SERVER['argv'] = ['-vv'];
		$result = $this->isVerbose();
		$this->assertTrue($result);

		$_SERVER['argv'] = ['-v', '-vv'];
		$result = $this->isVerbose();
		$this->assertTrue($result);

		$_SERVER['argv'] = ['-v'];
		$result = $this->isVerbose(true);
		$this->assertFalse($result);

		$_SERVER['argv'] = ['-vv'];
		$result = $this->isVerbose(true);
		$this->assertTrue($result);

		$_SERVER['argv'] = ['-v', '-vv'];
		$result = $this->isVerbose(true);
		$this->assertTrue($result);
	}

	/**
	 * @return void
	 */
	public function testInvokeMethod(): void {
		$class = UuidItemsTable::class;
		/** @var \TestApp\Model\Table\UuidItemsTable $object */
		$object = new $class();
		/** @uses \TestApp\Model\Table\UuidItemsTable::_newId() $result */
		$result = $this->invokeMethod($object, '_newId', [['id']]);

		$this->assertNotNull($result);
	}

	/**
	 * @return void
	 */
	public function testInvokeProperty(): void {
		$class = WheelsTable::class;
		/** @var \TestApp\Model\Table\WheelsTable $object */
		$object = new $class();
		$object->setTable('foo');
		$result = $this->invokeProperty($object, '_table');

		$this->assertSame('foo', $result);
	}

}
