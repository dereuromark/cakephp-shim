<?php

namespace Tools\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Shim\Model\Behavior\NullableBehavior;
use Shim\Model\Table\Table;
use Shim\TestSuite\TestCase;

class NullableBehaviorTest extends TestCase {

	/**
	 * @var array
	 */
	protected array $fixtures = [
		'plugin.Shim.Nullables',
		'plugin.Shim.NullableTenants',
	];

	/**
	 * @var \Cake\ORM\Table|\Shim\Model\Behavior\NullableBehavior
	 */
	protected $Table;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->Table = TableRegistry::getTableLocator()->get('Nullables');
		$this->Table->addAssociations(['hasOne' => ['NullableTenants' => ['hasMany' => 'Nullables']]]);
		$this->Table->addBehavior('Shim.Nullable');
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		$this->getTableLocator()->clear();
	}

	/**
	 * @return void
	 */
	public function testPatch(): void {
		$data = [
			'optional_id' => '',
			'required_id' => '',
			'string_optional' => '',
			'string_required' => '',
			'active_optional' => '',
			'active_required' => '',
			'datetime_optional' => '',
			'datetime_required' => '',
			'nullable_tenant' => null,
		];
		$entity = $this->Table->newEntity($data);

		$expected = [
			'optional_id' => null,
			'required_id' => null,
			'string_optional' => null,
			'string_required' => '',
			'active_optional' => null,
			'active_required' => null,
			'datetime_optional' => null,
			'datetime_required' => null,
			'nullable_tenant' => null,
		];
		$this->assertSame($expected, $entity->toArray());
	}

	/**
	 * @return void
	 */
	public function testPatchAssociation(): void {
		$data = [
			'optional_id' => '',
			'required_id' => '',
			'string_optional' => '',
			'string_required' => '',
			'active_optional' => '',
			'active_required' => '',
			'datetime_optional' => '',
			'datetime_required' => '',
			'tenant' => ['id' => 1],
		];
		$entity = $this->Table->newEntity($data);

		$expected = [
			'optional_id' => null,
			'required_id' => null,
			'string_optional' => null,
			'string_required' => '',
			'active_optional' => null,
			'active_required' => null,
			'datetime_optional' => null,
			'datetime_required' => null,
			'tenant' => ['id' => 1],
		];
		$this->assertSame($expected, $entity->toArray());
	}

	/**
	 * @return void
	 */
	public function testPatchOptionalNotNull(): void {
		$data = [
			'string_optional_notnull' => '',
			'active_optional_notnull' => '',
		];
		$entity = $this->Table->newEntity($data);

		$expected = [
			'string_optional_notnull' => '',
			'active_optional_notnull' => false,
		];
		$this->assertSame($expected, $entity->toArray());
	}

	/**
	 * @return void
	 */
	public function testSave(): void {
		$this->Table->removeBehavior('Nullable');
		$this->Table->addBehavior('Shim.Nullable', ['on' => 'beforeSave']);

		$data = [
			'optional_id' => '',
			'required_id' => '0',
			'string_optional' => '',
			'string_required' => '',
			'active_optional' => '',
			'active_required' => '0',
			'datetime_optional' => '',
			'datetime_required' => '2019-01-01 00:01:02',
			'nullable_tenant' => '',
		];
		$entity = $this->Table->newEntity($data);

		$expected = [
			'optional_id' => null,
			'required_id' => 0,
			'string_optional' => '',
			'string_required' => '',
			'active_optional' => null,
			'active_required' => false,
			'datetime_optional' => null,
			'nullable_tenant' => null,
		];
		$result = $entity->toArray();
		unset($result['datetime_required']);
		$this->assertSame($expected, $result);

		$entity = $this->Table->saveOrFail($entity);

		$expected = [
			'optional_id' => null,
			'required_id' => 0,
			'string_optional' => null,
			'string_required' => '',
			'active_optional' => null,
			'active_required' => false,
			'datetime_optional' => null,
			'nullable_tenant' => null,
		];
		$result = $entity->toArray();
		unset($result['id']);
		unset($result['datetime_required']);
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testSaveAssociation(): void {
		$this->Table->removeBehavior('Nullable');
		$this->Table->addBehavior('Shim.Nullable', ['on' => 'beforeSave']);

		$data = [
			'optional_id' => '',
			'required_id' => '0',
			'string_optional' => '',
			'string_required' => '',
			'active_optional' => '',
			'active_required' => '0',
			'datetime_optional' => '',
			'datetime_required' => '2019-01-01 00:01:02',
			'tenant' => ['id' => 1],
		];
		$entity = $this->Table->newEntity($data);

		$this->Table->saveOrFail($entity);

		$expected = [
			'optional_id' => null,
			'required_id' => 0,
			'string_optional' => null,
			'string_required' => '',
			'active_optional' => null,
			'active_required' => false,
			'datetime_optional' => null,
			'tenant' => ['id' => 1],
		];
		$result = $entity->toArray();
		unset($result['id']);
		unset($result['datetime_required']);
		$this->assertSame($expected, $result);
	}

}
