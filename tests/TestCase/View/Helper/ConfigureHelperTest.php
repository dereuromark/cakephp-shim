<?php

namespace Shim\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use RuntimeException;
use Shim\View\Helper\ConfigureHelper;

/**
 * ConfigureHelperTest class
 */
class ConfigureHelperTest extends TestCase {

	protected ConfigureHelper $_Configure;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$View = new View();
		$this->_Configure = new ConfigureHelper($View);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		unset($this->_Configure);
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testRead(): void {
		Configure::write('Deeply.nested.key', 'value');
		Configure::write('test', 'info');

		$result = $this->_Configure->read('Deeply.nested.key');
		$this->assertEquals('value', $result);

		$result = $this->_Configure->read('test');
		$this->assertEquals('info', $result);
	}

	/**
	 * @return void
	 */
	public function testCheck(): void {
		Configure::write('test', 'value');
		Configure::write('Flash.flash', 'value');

		$this->assertTrue($this->_Configure->check('test'));
		$this->assertTrue($this->_Configure->check('Flash.flash'));
		$this->assertFalse($this->_Configure->check('Does.not.exist'));
		$this->assertFalse($this->_Configure->check('Nope'));
	}

	/**
	 * @return void
	 */
	public function testConsume(): void {
		Configure::write('Deeply.nested.key', 'value');

		$result = $this->_Configure->consume('Deeply.nested.key');
		$this->assertEquals('value', $result);

		$result = $this->_Configure->consume('Deeply.nested.key');
		$this->assertNull($result);
	}

	/**
	 * @return void
	 */
	public function testReadOrFail(): void {
		Configure::write('Deeply.nested.key', 'value');

		$result = $this->_Configure->readOrFail('Deeply.nested.key');
		$this->assertEquals('value', $result);
	}

	/**
	 * @return void
	 */
	public function testReadOrFailFail(): void {
		$this->expectException(RuntimeException::class);

		$this->_Configure->readOrFail('Deeply.nested.key');
	}

	/**
	 * @return void
	 */
	public function testVersion(): void {
		$result = $this->_Configure->version();
		$this->assertNotEmpty($result);
	}

}
