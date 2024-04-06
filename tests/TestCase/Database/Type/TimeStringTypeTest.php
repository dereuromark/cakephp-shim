<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Type\TimeType;
use Cake\Database\TypeFactory;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\FormHelper;
use Cake\View\View;
use Shim\Database\Type\TimeStringType;
use Shim\Model\Table\Table;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\TimeTypesTable;

class TimeStringTypeTest extends TestCase {

	/**
	 * @var array<string>
	 */
	protected array $fixtures = [
		'plugin.Shim.TimeTypes',
	];

	/**
	 * @var \Shim\Model\Table\Table
	 */
	protected Table $Table;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		TypeFactory::map('time', TimeStringType::class);

		$this->Table = TableRegistry::getTableLocator()->get('TimeStringTypes', ['className' => TimeTypesTable::class]);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Table);
		TypeFactory::map('time', TimeType::class);
	}

	/**
	 * @return void
	 */
	public function testSave(): void {
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
	public function testSaveInvalid(): void {
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
	public function testSaveArray(): void {
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
	public function testFormControl(): void {
		$Form = new FormHelper(new View());

		$entity = $this->Table->newEmptyEntity();
		$Form->create($entity);
		$x = $Form->control('closing_time', ['type' => 'time']);
		$this->assertTextContains('<div class="input time"><label for="closing-time">Closing Time</label><input type="time" name="closing_time"', $x);
	}

	/**
	 * @return void
	 */
	public function testSaveNormalizeUpperBoundary(): void {
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
