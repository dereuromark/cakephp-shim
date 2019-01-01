<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Driver\Mysql;
use Cake\Database\Type;
use Cake\ORM\TableRegistry;
use Shim\Database\Type\JsonType;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\JsonTypesTable;

/**
 */
class JsonTypeTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Shim.JsonTypes'
	];

	/**
	 * @var \Shim\Model\Table\Table
	 */
	public $Table;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Type::map('json', JsonType::class);

		$this->Table = TableRegistry::get('JsonTypes', ['className' => JsonTypesTable::class]);

		$connection = $this->Table->getConnection()->config();
		$this->skipIf($connection['driver'] !== Mysql::class, 'Only for Mysql');
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Table);
	}

	/**
	 * @return void
	 */
	public function testFind() {
		$entities = $this->Table->find()->all()->toArray();
		$entity = $entities[0];
		$this->assertNull($entity->data);
		$this->assertNull($entity->data_required);
	}

	/**
	 * @return void
	 */
	public function testSave() {
		$data = [
			'name' => 'Foo',
			'data' => ['some' => 'thing'],
			'data_required' => ['some' => 'thing else']
		];
		$entity = $this->Table->newEntity($data);
		$result = $this->Table->save($entity);
		$this->assertTrue((bool)$result);

		$record = $this->Table->get($entity->id);
		$this->assertSame($data['data'], $record->data);
	}

	/**
	 * @return void
	 */
	public function testSaveNull() {
		$data = [
			'name' => 'Foo',
			'data' => null,
			'data_required' => ['some' => 'thing']
		];
		$entity = $this->Table->newEntity($data);
		$result = $this->Table->save($entity);
		$this->assertTrue((bool)$result);

		$record = $this->Table->get($entity->id);
		$this->assertNull($record->data);
		$this->assertSame($data['data_required'], $record->data_required);
	}

	/**
	 * data_required as not null field should throw "Column 'data_required' cannot be null" exception
	 *
	 * @expectedException \PDOException
	 * @return void
	 */
	public function testSaveNullInvalid() {
		$data = [
			'name' => 'Foo',
			'data' => null,
			'data_required' => null,
		];
		$entity = $this->Table->newEntity($data);
		$result = $this->Table->save($entity);
		$this->assertTrue((bool)$result);

		$this->Table->get($entity->id);
	}

}
