<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Type;
use Cake\Database\Type\TimeType;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\FormHelper;
use Cake\View\View;
use Shim\Database\Type\TimeStringType;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\TimeTypesTable;

class TimeStringTypeTest extends TestCase {

	/**
	 * @var array
	 */
	protected $fixtures = [
		'plugin.Shim.TimeTypes',
	];

	/**
	 * @var \Shim\Model\Table\Table
	 */
	protected $Table;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Type::map('time', TimeStringType::class);

		$this->Table = TableRegistry::get('TimeStringTypes', ['className' => TimeTypesTable::class]);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Table);
		Type::map('time', TimeType::class);
	}

	/**
	 * @return void
	 */
	public function testSave() {
		$data = [
			'name' => 'Foo',
			'closing_time' => '21:10:00',
		];
		$entity = $this->Table->newEntity($data);
		$this->Table->save($entity);

		$record = $this->Table->get($entity->id);
		$this->assertSame($data['closing_time'], $record->closing_time);
	}

	/**
	 * @return void
	 */
	public function testSaveInvalid() {
		$data = [
			'name' => 'Foo',
			'closing_time' => '25:10:00',
		];
		$entity = $this->Table->newEntity($data);
		$this->Table->save($entity);

		$record = $this->Table->get($entity->id);
		$this->assertNull($record->closing_time);
	}

	/**
	 * @return void
	 */
	public function testSaveArray() {
		$data = [
			'name' => 'Foo',
			'closing_time' => [
				'hour' => '1',
				'minute' => '12',
				'second' => '20',
			],
		];
		$entity = $this->Table->newEntity($data);
		$result = $this->Table->save($entity);
		$this->assertTrue((bool)$result);

		$record = $this->Table->get($entity->id);
		$this->assertSame('01:12:20', $record->closing_time);
	}

	/**
	 * @return void
	 */
	public function testFormControl() {
		$Form = new FormHelper(new View());

		$entity = $this->Table->newEmptyEntity();
		$Form->create($entity);
		$x = $Form->control('closing_time', ['type' => 'time']);
		$this->assertTextContains('<div class="input time"><label for="closing-time">Closing Time</label><input type="time" name="closing_time"', $x);
	}

	/**
	 * @return void
	 */
	public function testSaveNormalizeUpperBoundary() {
		TimeStringType::$normalizeUpperBoundary = true;

		$data = [
			'name' => 'Foo',
			'closing_time' => '24:00:00',
		];
		$entity = $this->Table->newEntity($data);
		$this->Table->save($entity);

		$record = $this->Table->get($entity->id);
		$this->assertSame('00:00:00', $record->closing_time);

		TimeStringType::$normalizeUpperBoundary = false;
	}

}
