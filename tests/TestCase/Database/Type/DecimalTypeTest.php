<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Type;
use Cake\Database\Type\DecimalType as CoreDecimalType;
use Cake\ORM\TableRegistry;
use Shim\Database\Type\DecimalType;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\DecimalTypesTable;

class DecimalTypeTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Shim.DecimalTypes'
	];

	/**
	 * @var \Shim\Database\Type\DecimalType
	 */
	protected $type;

	/**
	 * @var \Shim\Model\Table\Table
	 */
	protected $Table;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Type::map('decimal', DecimalType::class);

		$this->type = Type::build('decimal');
		$this->Table = TableRegistry::get('DecimalTypes', ['className' => DecimalTypesTable::class]);
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Table);
		Type::map('decimal', CoreDecimalType::class);
	}

	/**
	 * Test marshalling
	 *
	 * @return void
	 */
	public function testMarshal() {
		$result = $this->type->marshal('some data');
		$this->assertNull($result);

		$result = $this->type->marshal('');
		$this->assertNull($result);

		$result = $this->type->marshal('2.51');
		$this->assertSame('2.51', $result);

		// allow custom decimal format (@see https://github.com/cakephp/cakephp/issues/12800)
		$result = $this->type->marshal('1 230,73');
		$this->assertSame('1 230,73', $result);

		$result = $this->type->marshal('3.5 bears');
		$this->assertNull($result);

		$result = $this->type->marshal(['3', '4']);
		$this->assertNull($result);

		$result = $this->type->marshal('0.1234567890123456789');
		$this->assertSame('0.1234567890123456789', $result);

		// This test is to indicate the problem that will occur if you use
		// float/double values which get converted to scientific notation by PHP.
		// To avoid this problem always using strings to indicate decimals values.
		$result = $this->type->marshal(1234567890123456789.2);
		$this->assertSame('1.2345678901235E+18', $result);
	}

	/**
	 * @return void
	 */
	public function testSave() {
		$data = [
			'required' => '-1.1',
			'optional' => '0.22'
		];
		$entity = $this->Table->newEntity($data);

		$this->assertSame($data['required'], $entity->required);
		$this->assertSame($data['optional'], $entity->optional);

		$this->Table->saveOrFail($entity);
	}

	/**
	 * @return void
	 */
	public function testSaveInvalidPartial() {
		$data = [
			'required' => 1.1,
			'optional' => 'Foo',
		];
		$entity = $this->Table->newEntity($data);

		$this->assertSame('1.1', $entity->required);
		$this->assertNull($entity->optional);

		$this->Table->saveOrFail($entity);
	}

}
