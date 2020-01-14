<?php

namespace Shim\Test\TestCase\TestSuite;

use Cake\Database\Schema\TableSchema;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\WheelsTable;

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
	public function testDebug() {
		$_SERVER['argv'] = ['-v'];

		ob_start();
		$this->debug('Foo');
		$result = ob_get_clean();
		$this->assertTextContains('Foo', $result);
	}

	/**
	 * @return void
	 */
	public function testIsDebug() {
		$result = $this->isDebug();
		$this->assertFalse($result);

		$_SERVER['argv'] = ['--debug'];
		$result = $this->isDebug();
		$this->assertTrue($result);
	}

	/**
	 * @return void
	 */
	public function testIsVerbose() {
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
	public function testInvokeMethod() {
		$class = WheelsTable::class;
		/** @var \TestApp\Model\Table\WheelsTable $object */
		$object = new $class();
		$tableSchema = new TableSchema('foo');
		/** @uses \TestApp\Model\Table\WheelsTable::_initializeSchema() $result */
		$result = $this->invokeMethod($object, '_initializeSchema', [$tableSchema]);

		$this->assertSame($tableSchema, $result);
	}

	/**
	 * @return void
	 */
	public function testInvokeProperty() {
		$class = WheelsTable::class;
		/** @var \TestApp\Model\Table\WheelsTable $object */
		$object = new $class();
		$object->setTable('foo');
		$result = $this->invokeProperty($object, '_table');

		$this->assertSame('foo', $result);
	}

}
