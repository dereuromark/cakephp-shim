<?php
namespace Shim\Test\TestCase\Model\Table;

use Shim\TestSuite\TestCase;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Shim\Model\Table\Table;
use Cake\Database\ValueBinder;

class TableTest extends TestCase {

	public $Posts;

	public $Users;

	public $fixtures = ['core.users', 'core.posts', 'core.authors', 'plugin.Shim.Wheels', 'plugin.Shim.Cars'];

	public function setUp() {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		$this->Posts = TableRegistry::get('Shim.Posts', ['className' => '\Shim\Model\Table\Table']);
		$this->Posts->belongsTo('Authors');
		$this->Users = TableRegistry::get('Shim.Users', ['className' => '\Shim\Model\Table\Table']);

		Configure::delete('Shim');
	}

	public function tearDown() {
		Configure::delete('Shim');

		parent::tearDown();
	}

	public function testInstance() {
		$this->assertInstanceOf('\Shim\Model\Table\Table', $this->Posts);
		$this->assertInstanceOf('\Shim\Model\Table\Table', $this->Users);
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
	 * ShimModelTest::testGetFail()
	 *
	 * @expectedException \Cake\Datasource\Exception\RecordNotFoundException
	 * @return void
	 */
	public function testGetFail() {
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
	 * Shim support for saving via saveField() similar to 2.x
	 *
	 * @return void
	 */
	public function testSaveField() {
		$post = $this->Posts->find('first');
		$this->assertInstanceOf('\Cake\ORM\Entity', $post);

		$res = $this->Posts->saveField($post['id'], 'title', 'FooBar');
		$this->assertTrue((bool)$res);

		$post = $this->Posts->record($post['id']);
		$this->assertEquals('FooBar', $post['title']);
	}

	/**
	 * Shim support for saving arrays directly.
	 *
	 * @return void
	 */
	public function testSaveArray() {
		$array = [
			'title' => 'Foo',
			'author_id' => 1,
		];
		$res = $this->Posts->saveArray($array);
		$this->assertTrue((bool)$res);
		$this->assertNotEmpty($res->id);
	}

	/**
	 * Shim support for 2.x relation arrays
	 *
	 * @return void
	 */
	public function testRelationShims() {
		$this->Wheels = TableRegistry::get('Wheels');
		$this->assertInstanceOf('\Shim\Model\Table\Table', $this->Wheels);

		$car = $this->Wheels->Cars->find()->first();
		$this->assertInstanceOf('\Cake\ORM\Entity', $car);

		$this->Cars = TableRegistry::get('Cars');
		$this->assertInstanceOf('\Shim\Model\Table\Table', $this->Cars);

		$wheels = $this->Cars->Wheels->find()->where(['car_id' => $car['id']]);
		$wheels->execute();

		$order = $wheels->clause('order');
		$sql = $order->sql(new ValueBinder());
		$this->assertRegExp('/["`]Wheels["`]\.["`]position["`] ASC/i', $sql);

		$this->assertSame(2, count($wheels->toArray()));

		$displayField = $this->Wheels->displayField();
		$this->assertSame('position', $displayField);

		$car = $this->Wheels->BogusCars->find()->first();
		$this->assertInstanceOf('\Cake\ORM\Entity', $car);

		$car = $this->Wheels->HABTMCars->find()->first();
		$this->assertInstanceOf('\Cake\ORM\Entity', $car);
	}

	/**
	 * Shim support for 2.x validation arrays
	 *
	 * @return void
	 */
	public function testBehaviorShims() {
		$this->Wheels = TableRegistry::get('Wheels');
		$behaviors = $this->Wheels->behaviors()->loaded();
		$expected = ['Useless'];
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
		$this->assertNotSame([], $wheel->errors());
		$result = $this->Wheels->save($wheel);
		$this->assertFalse($result);

		// i18n array validation setup
		//$this->Wheels = TableRegistry::get('Wheels');
		$wheel = $this->Wheels->newEntity(['position' => '12345678901234567890abc']);
		$expected = [
			'position' => [
				'maxLength' => 'valErrMaxCharacters xyz 20'
			]
		];
		$this->assertSame($expected, $wheel->errors());
		$result = $this->Wheels->save($wheel);
		$this->assertFalse($result);

		$wheel = $this->Wheels->newEntity(['position' => 'rear left']);
		$result = $this->Wheels->save($wheel);
		$this->assertTrue((bool)$result);
	}

}
