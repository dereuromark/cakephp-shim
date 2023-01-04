<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Cake\Database\TypeFactory;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Shim\Database\Type\ArrayType;
use stdClass;

/**
 * Test for the Array type.
 */
class ArrayTypeTest extends TestCase {

	/**
	 * @var \Shim\Database\Type\ArrayType
	 */
	protected ArrayType $type;

	protected Driver|MockObject $driver;

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		TypeFactory::map('array', ArrayType::class);

		$this->type = TypeFactory::build('array');
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
	 * @return void
	 */
	public function testMarshal(): void {
		$result = $this->type->marshal(null);
		$this->assertNull($result);

		$result = $this->type->marshal([]);
		$this->assertSame([], $result);

		$result = $this->type->marshal(['foo', 'bar']);
		$this->assertSame(['foo', 'bar'], $result);

		$result = $this->type->marshal(new stdClass());
		$this->assertNull($result);
	}

	/**
	 * Test toPHP
	 *
	 * @return void
	 */
	public function testToPHP(): void {
		$result = $this->type->toPHP(null, $this->driver);
		$this->assertNull($result);

		$result = $this->type->toPHP([], $this->driver);
		$this->assertSame([], $result);

		$result = $this->type->toPHP(['foo', 'bar'], $this->driver);
		$this->assertSame(['foo', 'bar'], $result);

		$result = $this->type->toPHP(new stdClass(), $this->driver);
		$this->assertNull($result);
	}

	/**
	 * @return void
	 */
	public function testToDatabase(): void {
		$result = $this->type->toDatabase(null, $this->driver);
		$this->assertNull($result);

		$result = $this->type->toDatabase([], $this->driver);
		$this->assertNull($result);

		$result = $this->type->toDatabase('foo', $this->driver);
		$this->assertSame('foo', $result);

		$result = $this->type->toDatabase('', $this->driver);
		$this->assertSame('', $result);

		$result = $this->type->toDatabase(new stdClass(), $this->driver);
		$this->assertNull($result);
	}

}
