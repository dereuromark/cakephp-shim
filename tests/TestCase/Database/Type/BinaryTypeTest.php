<?php
namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Type;
use Shim\Database\Type\BinaryType;
use Cake\TestSuite\TestCase;
use \PDO;

/**
 * Test for the Uuid type.
 */
class BinaryTypeTest extends TestCase {

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Type::map('binary', 'Shim\Database\Type\BinaryType');

		$this->type = Type::build('binary');
		$this->driver = $this->getMock('Cake\Database\Driver');
	}

	/**
	 * Test toPHP
	 *
	 * @return void
	 */
	public function testToPHP() {
		$this->assertNull($this->type->toPHP(null, $this->driver));

		$result = $this->type->toPHP('5945c961-e74d-478f-8afe-da53cf4189e3', $this->driver);
		$this->assertSame('5945c961-e74d-478f-8afe-da53cf4189e3', $result);

		$result = $this->type->toPHP('some data', $this->driver);
		$this->assertInternalType('resource', $result);
	}

	/**
	 * Test generating new ids
	 *
	 * @return void
	 */
	public function testNewId() {
		$one = $this->type->newId();
		$two = $this->type->newId();

		$this->assertNotEquals($one, $two, 'Should be different values');
		$this->assertRegExp('/^[a-f0-9-]+$/', $one, 'Should quack like a uuid');
		$this->assertRegExp('/^[a-f0-9-]+$/', $two, 'Should quack like a uuid');
	}

}
