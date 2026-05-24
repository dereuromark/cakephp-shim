<?php

namespace Shim\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Cake\Database\TypeFactory;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\FormHelper;
use Cake\View\View;
use Shim\Database\Type\YearType;
use Shim\Model\Table\Table;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\YearTypesTable;

class YearTypeTest extends TestCase {

	/**
	 * @var array<string>
	 */
	protected array $fixtures = [
		'plugin.Shim.YearTypes',
	];

	protected Table $Table;

	/**
	 * @return void
	 */
	public function setUp(): void {
		TypeFactory::map('year', YearType::class);

		$this->Table = TableRegistry::getTableLocator()->get('YearTypes', ['className' => YearTypesTable::class]);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		unset($this->Table);
	}

	/**
	 * @return void
	 */
	public function testSave(): void {
		$data = [
			'name' => 'Foo',
			'year_of_birth' => '2015',
		];
		$entity = $this->Table->newEntity($data);
		$this->Table->save($entity);

		$record = $this->Table->get($entity->id);
		$this->assertSame(2015, $record->year_of_birth);
	}

	/**
	 * Regression: `toDatabase()` and `marshal()` must return `int` (not the
	 * unmodified numeric string). Without the cast the declared `?int` return
	 * type is violated under strict_types.
	 *
	 * @return void
	 */
	public function testToDatabaseAndMarshalReturnInt(): void {
		$type = new YearType('year');
		$driver = $this->getMockBuilder(Driver::class)->disableOriginalConstructor()->getMock();

		$this->assertSame(2024, $type->toDatabase('2024', $driver));
		$this->assertSame(2024, $type->marshal('2024'));
		$this->assertSame(2024, $type->marshal(['year' => '2024']));
		$this->assertNull($type->toDatabase(null, $driver));
		$this->assertNull($type->marshal(''));
	}

	/**
	 * @return void
	 */
	public function _testSaveYearArray(): void {
		$data = [
			'name' => 'Foo',
			'year_of_birth' => [
				'day' => '1',
				'month' => '12',
				'year' => '2015',
			],
		];
		$entity = $this->Table->newEntity($data);
		$result = $this->Table->save($entity);
		$this->assertTrue((bool)$result);

		$record = $this->Table->get($entity->id);
		debug($record);
	}

	/**
	 * @return void
	 */
	public function testFormControl(): void {
		$Form = new FormHelper(new View());

		$entity = $this->Table->newEmptyEntity();
		$Form->create($entity);
		$html = $Form->control('year_of_birth', ['type' => 'year']);
		$this->assertStringContainsString('<select name="year_of_birth"', $html);
		// <div class="input number"><label for="year-of-birth">Year Of Birth</label><input type="number" name="year_of_birth" id="year-of-birth"/></div>
	}

}
