<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Type;
use Cake\ORM\TableRegistry;
use Shim\Database\Type\StringType;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\StringTypesTable;

class StringTypeTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Shim.StringTypes'
	];

	/**
	 * @var \Shim\Database\Type\StringType
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

		Type::map('string', StringType::class);

		$this->type = Type::build('string');
		$this->Table = TableRegistry::get('StringTypes', ['className' => StringTypesTable::class]);
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Table);
	}

	/**
	 * Test marshalling
	 *
	 * @return void
	 */
	public function testMarshal() {
		$this->assertNull($this->type->marshal(null));
		$this->assertNull($this->type->marshal([1, 2, 3]));
		$this->assertSame('word', $this->type->marshal('word'));
		$this->assertSame('2.123', $this->type->marshal(2.123));
	}

	/**
	 * @return void
	 */
	public function testSave() {
		$data = [
			'name' => 'Foo',
			'optional' => 'Bar'
		];
		$entity = $this->Table->newEntity($data);

		$this->assertSame($data['name'], $entity->name);
		$this->assertSame($data['optional'], $entity->optional);

		$this->Table->saveOrFail($entity);
	}

	/**
	 * @return void
	 */
	public function testSaveInvalid() {
		$data = [
			'name' => 'Foo',
			'optional' => [
				'day' => '1',
				'month' => '12',
				'year' => '2015'
			]
		];
		$entity = $this->Table->newEntity($data);

		$this->assertNull($entity->optional);

		$this->Table->saveOrFail($entity);
	}

}
