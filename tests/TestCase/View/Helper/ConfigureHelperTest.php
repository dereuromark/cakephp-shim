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

	/**
	 * @var \Shim\View\Helper\ConfigureHelper
	 */
	protected $_Configure;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->_View = new View();
		$this->_Configure = new ConfigureHelper($this->_View);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		unset($this->_View, $this->_Configure);
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testRead() {
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
	public function testCheck() {
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
	public function testConsume() {
		Configure::write('Deeply.nested.key', 'value');

		$result = $this->_Configure->consume('Deeply.nested.key');
		$this->assertEquals('value', $result);

		$result = $this->_Configure->consume('Deeply.nested.key');
		$this->assertNull($result);
	}

	/**
	 * @return void
	 */
	public function testReadOrFail() {
		Configure::write('Deeply.nested.key', 'value');

		$result = $this->_Configure->readOrFail('Deeply.nested.key');
		$this->assertEquals('value', $result);
	}

	/**
	 * @return void
	 */
	public function testReadOrFailFail() {
		$this->expectException(RuntimeException::class);

		$this->_Configure->readOrFail('Deeply.nested.key');
	}

	/**
	 * @return void
	 */
	public function testVersion() {
		$result = $this->_Configure->version();
		$this->assertNotEmpty($result);
	}

}
