<?php

namespace Shim\Test\TestCase\Database\Type;

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
		parent::setUp();

		TypeFactory::map('year', YearType::class);

		$this->Table = TableRegistry::getTableLocator()->get('YearTypes', ['className' => YearTypesTable::class]);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

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
