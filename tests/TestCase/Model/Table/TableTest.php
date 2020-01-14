<?php

namespace Shim\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\Database\ValueBinder;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Shim\Model\Table\Table;
use Shim\TestSuite\TestCase;

class TableTest extends TestCase {

	/**
	 * @var \Shim\Model\Table\Table
	 */
	protected $Posts;

	/**
	 * @var \Shim\Model\Table\Table
	 */
	protected $Users;

	/**
	 * @var \Shim\Model\Table\Table
	 */
	protected $Wheels;

	/**
	 * @var array
	 */
	protected $fixtures = [
		'core.Users',
		'core.Posts',
		'core.Authors',
		'plugin.Shim.Wheels',
		'plugin.Shim.Cars',
		'plugin.Shim.CarsWheels',
	];

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		$this->Posts = TableRegistry::get('Shim.Posts', ['className' => '\Shim\Model\Table\Table']);
		$this->Posts->belongsTo('Authors');
		$this->Users = TableRegistry::get('Shim.Users', ['className' => '\Shim\Model\Table\Table']);

		Configure::delete('Shim');
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		Configure::delete('Shim');

		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testInstance() {
		$this->assertInstanceOf(Table::class, $this->Posts);
		$this->assertInstanceOf(Table::class, $this->Users);
	}

	/**
	 * ShimModelTest::testGet()
	 *
	 * @return void
	 */
	public function testMagicFind() {
		$res = $this->Posts->findById(2);
		$this->assertNotEmpty($res->toArray());

		$res = $this->Posts->findById(121212);
		$this->assertEmpty($res->toArray());
	}

	/**
	 * Test the better findById()
	 *
	 * @return void
	 */
	public function testGet() {
		$record = $this->Posts->get(2);
		$this->assertEquals(2, $record['id']);

		$record = $this->Posts->get(2, ['fields' => ['id', 'published']]);
		$this->assertEquals(2, count($record->toArray()));

		$record = $this->Posts->get(2, ['fields' => ['id', 'title', 'body', 'author_id', 'Authors.id'], 'contain' => ['Authors']]);
		$this->assertEquals(5, count($record->toArray()));
		$this->assertEquals(3, $record->author['id']);
	}

	/**
	 * @return void
	 */
	public function testGetFail() {
		$this->expectException(RecordNotFoundException::class);

		$this->Posts->get(2222);
	}

	/**
	 * Test the better findById()
	 *
	 * @return void
	 */
	public function testRecord() {
		$record = $this->Posts->record(2);
		$this->assertEquals(2, $record['id']);

		$record = $this->Posts->record(2, ['fields' => ['id', 'published']]);
		$this->assertEquals(2, count($record->toArray()));

		$record = $this->Posts->record(2, ['fields' => ['id', 'title', 'body', 'author_id', 'Authors.id'], 'contain' => ['Authors']]);
		$this->assertEquals(5, count($record->toArray()));
		$this->assertEquals(3, $record->author['id']);
	}

	/**
	 * ShimModelTest::testRecordFail()
	 *
	 * @return void
	 */
	public function testRecordFail() {
		$res = $this->Posts->record(2222);
		$this->assertNull($res);
	}

	/**
	 * @return void
	 */
	public function testField() {
		$is = $this->Posts->field('title');
		$this->assertSame('First Post', $is);
	}

	/**
	 * @return void
	 */
	public function testFieldByConditions() {
		$is = $this->Posts->fieldByConditions('title', ['title LIKE' => 'S%']);
		$this->assertSame('Second Post', $is);

		$is = $this->Posts->fieldByConditions('title', ['title LIKE' => '%'], ['order' => ['title' => 'DESC']]);
		$this->assertSame('Third Post', $is);
	}

	/**
	 * This does not throw an exception anymore as it did in 2.x.
	 *
	 * @return void
	 */
	public function testFieldInvalid() {
		$res = $this->Posts->field('fooooo');
		$this->assertNull($res);
	}

	/**
	 * Shim support for saving arrays directly.
	 *
	 * @return void
	 */
	public function testSaveAll() {
		$array = [
			[
				'title' => 'Foo',
				'author_id' => 1,
			],
			[
				'title' => 'Bar',
				'author_id' => 2,
			],
		];
		$entities = $this->Posts->newEntities($array);
		$this->assertSame(2, count($entities));

		$this->assertTrue($this->Posts->saveAll($entities));

		$array[] = [
			'title' => 'Gez',
			'author_id' => null,
		];
		/** @var \Cake\ORM\Entity[] $entities */
		$entities = $this->Posts->newEntities($array);
		$entities[2]->setError('title', 'Some fake error reason');

		$this->assertSame(3, count($entities));

		$this->assertFalse($this->Posts->saveAll($entities));
	}

	/**
	 * @return void
	 */
	public function testAutoNullConditionsArray() {
		$conditions = [
			'foo' => 1,
			'bar' => null,
		];
		$result = $this->Posts->autoNullConditionsArray($conditions);

		$expected = [
			'foo' => 1,
			'bar IS' => null,
		];
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test that find('list') also works as it used to in 2.x.
	 *
	 * @return void
	 */
	public function testFindListAutoSelectedFields() {
		$this->Users->setDisplayField('nick');

		$query = $this->Users->find('list', ['fields' => ['id', 'created']]);
		$expected = ['id', 'created'];
		$this->assertSame($expected, $query->clause('select'));
		$results = $query->toArray();
		$this->assertInstanceOf(FrozenTime::class, array_shift($results));

		$query = $this->Users->find('list', ['fields' => ['id']]);
		$expected = ['id'];
		$this->assertSame($expected, $query->clause('select'));
		$expected = [
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
		];
		$this->assertSame($expected, $query->toArray());
	}

	/**
	 * @return void
	 */
	public function testArrayCondition() {
		$result = $this->Posts->find()->all();
		// ID 1, 2, 3
		$this->assertSame(3, count($result));

		$query = $this->Posts->find();
		$result = $this->Posts->arrayCondition($query, 'id', [1, 2])->all();
		// ID 1, 2
		$this->assertSame(2, count($result));

		$query = $this->Posts->find();
		$result = $this->Posts->arrayCondition($query, 'id NOT', [1, 2])->all();
		// ID 3
		$this->assertSame(1, count($result));

		$query = $this->Posts->find();
		$result = $this->Posts->arrayCondition($query, 'id', [])->all();
		// nothing
		$this->assertSame(0, count($result));

		$query = $this->Posts->find();
		$result = $this->Posts->arrayCondition($query, 'id NOT', [])->all();
		// ID 1, 2, 3
		$this->assertSame(3, count($result));
	}

	/**
	 * @return void
	 */
	public function testArrayConditionArray() {
		$result = $this->Posts->find()->all();
		// ID 1, 2, 3
		$this->assertSame(3, count($result));

		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id', [1, 2, 3]))->all();
		// ID 1, 2, 3
		$this->assertSame(3, count($result));

		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id', [1, 3]))->all();
		// ID 1, 3
		$this->assertSame(2, count($result));

		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id', [1]))->all();
		// ID 1
		$this->assertSame(1, count($result));

		// BUGFIX: The core would treat IN + [] as exception :(
		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id', []))->all();
		// nothing
		$this->assertSame(0, count($result));

		// Logically, IN + [] should be equal to always false condition
	}

	/**
	 * @return void
	 */
	public function testArrayConditionArrayNot() {
		$result = $this->Posts->find()->all();
		// ID 1, 2, 3
		$this->assertSame(3, count($result));

		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id NOT', [1, 2, 3]))->all();
		// nothing
		$this->assertSame(0, count($result));

		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id NOT', [1, 3]))->all();
		// ID 2
		$this->assertSame(1, count($result));

		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id NOT', [1]))->all();
		// ID 2, 3
		$this->assertSame(2, count($result));

		// BUGFIX: The core would treat NOT IN + [] as exception :(
		$result = $this->Posts->find()->where($this->Posts->arrayConditionArray('id NOT', []))->all();
		// ID 1, 2, 3
		$this->assertSame(3, count($result));

		// Logically, NOT IN + [] should be equal to no condition (or always true condition)
	}

	/**
	 * Shim support for 2.x relation arrays
	 *
	 * @return void
	 */
	public function testRelationShims() {
		$this->Wheels = TableRegistry::get('Wheels');
		$this->assertInstanceOf(Table::class, $this->Wheels);

		$car = $this->Wheels->Cars->find()->first();
		$this->assertInstanceOf(Entity::class, $car);

		$this->Cars = TableRegistry::get('Cars');
		$this->assertInstanceOf(Table::class, $this->Cars);

		$wheels = $this->Cars->Wheels->find()->where(['car_id' => $car['id']]);
		$wheels->execute();

		$order = $wheels->clause('order');
		$sql = $order->sql(new ValueBinder());
		$this->assertRegExp('/["`]Wheels["`]\.["`]position["`] ASC/i', $sql);

		$this->assertSame(2, count($wheels->toArray()));

		$displayField = $this->Wheels->getDisplayField();
		$this->assertSame('position', $displayField);

		$car = $this->Wheels->BogusCars->find()->first();
		$this->assertInstanceOf(Entity::class, $car);

		//$car = $this->Wheels->HABTMCars->find()->first();
		//$this->assertInstanceOf(Entity::class, $car);
	}

	/**
	 * Shim support for 2.x validation arrays
	 *
	 * @return void
	 */
	public function testBehaviorShims() {
		$this->Wheels = TableRegistry::get('Cars');
		$behaviors = $this->Wheels->behaviors()->loaded();
		$expected = ['Useless', 'Timestamp'];
		$this->assertSame($expected, $behaviors);
	}

	/**
	 * Shim support for 2.x validation arrays
	 *
	 * @return void
	 */
	public function testBehaviorShimDisableTimestamp() {
		$this->Wheels = TableRegistry::get('Wheels');
		$behaviors = $this->Wheels->behaviors()->loaded();
		$expected = [];
		$this->assertSame($expected, $behaviors);
	}

	/**
	 * Shim support for 2.x validation arrays
	 *
	 * @return void
	 */
	public function testValidationShims() {
		$this->Wheels = TableRegistry::get('Wheels');

		$wheel = $this->Wheels->newEntity(['position' => '']);
		$this->assertNotSame([], $wheel->getErrors());
		$result = $this->Wheels->save($wheel);
		$this->assertFalse($result);

		// i18n array validation setup
		//$this->Wheels = TableRegistry::get('Wheels');
		$wheel = $this->Wheels->newEntity(['position' => '12345678901234567890abc']);
		$expected = [
			'position' => [
				'maxLength' => 'valErrMaxCharacters xyz 20',
			],
		];
		$this->assertSame($expected, $wheel->getErrors());
		$result = $this->Wheels->save($wheel);
		$this->assertFalse($result);

		$wheel = $this->Wheels->newEntity(['position' => 'rear left']);
		$result = $this->Wheels->save($wheel);
		$this->assertTrue((bool)$result);

		$wheel = $this->Wheels->newEntity(['position' => 'rear left', 'car_id' => 'a']);
		$expected = [
			'car_id' => [
				'numeric' => 'The provided value is invalid',
			],
		];
		$this->assertSame($expected, $wheel->getErrors());

		$wheel = $this->Wheels->newEntity(['position' => 'rear left', 'car_id' => '1']);
		$result = $this->Wheels->save($wheel);
		$this->assertTrue((bool)$result);
	}

	/**
	 * @return void
	 */
	public function testSaveStrict() {
		$this->Wheels = TableRegistry::get('Wheels');

		$wheel = $this->Wheels->newEntity(['position' => '']);
		$this->assertNotSame([], $wheel->getErrors());

		$this->expectException(InvalidArgumentException::class);

		$this->Wheels->save($wheel, ['strict' => true]);
	}

}
