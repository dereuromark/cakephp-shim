<?php
namespace Shim\Test\TestCase\Model\Table;

use Shim\TestSuite\TestCase;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Shim\Model\Table\Table;

class TableTest extends TestCase {

	public $Posts;

	public $Users;

	public $fixtures = ['core.users', 'core.posts', 'core.authors'];

	public function setUp() {
		parent::setUp();

		$this->Posts = TableRegistry::get('Shim.Posts', ['className' => '\Shim\Model\Table\Table']);
		$this->Posts->belongsTo('Authors');
		$this->Users = TableRegistry::get('Shim.Users', ['className' => '\Shim\Model\Table\Table']);

		Configure::delete('Shim');
	}

	public function tearDown() {
		Configure::delete('Shim');

		parent::tearDown();
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
		$this->assertSame([], $res);
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
	public function testSaveArray() {
		$array = array(
			'title' => 'Foo',
			'author_id' => 1,
		);
		$res = $this->Posts->saveArray($array);
		$this->assertTrue((bool)$res);
		$this->assertNotEmpty($res->id);
	}

}
