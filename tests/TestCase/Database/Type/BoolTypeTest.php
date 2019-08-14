<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Type;
use Cake\Database\Type\BoolType as CoreBoolType;
use Cake\ORM\TableRegistry;
use Shim\Database\Type\BoolType;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\BoolTypesTable;

class BoolTypeTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Shim.BoolTypes'
	];

	/**
	 * @var \Shim\Database\Type\BoolType
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

		Type::map('boolean', BoolType::class);

		$this->type = Type::build('boolean');
		$this->Table = TableRegistry::get('BoolTypes', ['className' => BoolTypesTable::class]);
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Table);
		Type::map('boolean', CoreBoolType::class);
	}

	/**
	 * Test marshalling booleans
	 *
	 * @return void
	 */
	public function testMarshal() {
		$this->assertNull($this->type->marshal(null));
		$this->assertTrue($this->type->marshal(true));
		$this->assertTrue($this->type->marshal(1));
		$this->assertTrue($this->type->marshal('1'));
		$this->assertTrue($this->type->marshal('true'));
		$this->assertTrue($this->type->marshal('on'));

		$this->assertFalse($this->type->marshal(false));
		$this->assertFalse($this->type->marshal('false'));
		$this->assertFalse($this->type->marshal('0'));
		$this->assertFalse($this->type->marshal(0));
		$this->assertFalse($this->type->marshal('off'));
		$this->assertNull($this->type->marshal(''));
		$this->assertNull($this->type->marshal('not empty'));
		$this->assertNull($this->type->marshal(['2', '3']));
	}

	/**
	 * @return void
	 */
	public function testNewEntity() {
		$data = [
			'required' => 'false',
			'optional' => 'Yes',
		];
		$entity = $this->Table->newEntity($data);
		$this->Table->save($entity);

		$record = $this->Table->get($entity->id);
		$this->assertFalse($record->required);
		$this->assertTrue($record->optional);
	}

	/**
	 * @return void
	 */
	public function testNewEntityInvalid() {
		$data = [
			'required' => 'not empty',
			'optional' => [
				'day' => '1',
				'month' => '12',
				'year' => '2015'
			],
		];
		$entity = $this->Table->newEntity($data);

		$this->assertNull($entity->required);
		$this->assertNull($entity->optional);
	}

}
