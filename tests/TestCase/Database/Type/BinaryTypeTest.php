<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Cake\Database\TypeFactory;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Shim\Database\Type\BinaryType;

/**
 * Test for the Uuid type.
 */
class BinaryTypeTest extends TestCase {

	/**
	 * @var \Shim\Database\Type\BinaryType
	 */
	protected BinaryType $type;

	protected Driver|MockObject $driver;

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		TypeFactory::map('binary', BinaryType::class);

		$this->type = TypeFactory::build('binary');
		$this->driver = $this->getMockBuilder(Driver::class)->getMock();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Table);
	}

	/**
	 * Test toPHP
	 *
	 * @return void
	 */
	public function testToPHP(): void {
		$this->assertNull($this->type->toPHP(null, $this->driver));

		$result = $this->type->toPHP('5945c961-e74d-478f-8afe-da53cf4189e3', $this->driver);
		$this->assertSame('5945c961-e74d-478f-8afe-da53cf4189e3', $result);

		$result = $this->type->toPHP('some data', $this->driver);
		$this->assertIsResource($result);
	}

	/**
	 * Test generating new ids
	 *
	 * @return void
	 */
	public function testNewId(): void {
		$one = $this->type->newId();
		$two = $this->type->newId();

		$this->assertNotEquals($one, $two, 'Should be different values');
		$this->assertMatchesRegularExpression('/^[a-f0-9-]+$/', $one, 'Should quack like a uuid');
		$this->assertMatchesRegularExpression('/^[a-f0-9-]+$/', $two, 'Should quack like a uuid');
	}

}
