<?php

namespace Shim\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Shim\TestSuite\TestCase;

class NullableBehaviorTest extends TestCase {

	/**
	 * @var array<string>
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

	/**
	 * Test nullable enum-like string fields (simulating enum behavior).
	 *
	 * When forms submit nullable enum fields:
	 * - Without NullableBehavior: empty string ('') would be sent to DB, causing error for enum columns
	 * - With NullableBehavior: empty string is converted to null, which DB accepts
	 *
	 * @return void
	 */
	public function testPatchNullableEnumField(): void {
		$data = [
			'status_optional' => '',
			'status_required' => '',
		];
		$entity = $this->Table->newEntity($data);

		$expected = [
			'status_optional' => null,
			'status_required' => '',
		];
		$this->assertSame($expected, $entity->toArray());
	}

	/**
	 * Test that nullable enum fields with actual values are preserved.
	 *
	 * @return void
	 */
	public function testPatchNullableEnumFieldWithValue(): void {
		$data = [
			'status_optional' => 'active',
			'status_required' => 'pending',
		];
		$entity = $this->Table->newEntity($data);

		$expected = [
			'status_optional' => 'active',
			'status_required' => 'pending',
		];
		$this->assertSame($expected, $entity->toArray());
	}

	/**
	 * Test saving nullable enum fields with beforeSave mode.
	 *
	 * This simulates the complete form-to-database flow where:
	 * 1. Form submits empty string for unselected nullable enum
	 * 2. Behavior converts to null before save
	 * 3. Database accepts null for nullable enum column
	 *
	 * @return void
	 */
	public function testSaveNullableEnumField(): void {
		$this->Table->removeBehavior('Nullable');
		$this->Table->addBehavior('Shim.Nullable', ['on' => 'beforeSave']);

		$data = [
			'required_id' => '1',
			'string_required' => 'test',
			'active_required' => '1',
			'datetime_required' => '2019-01-01 00:01:02',
			'status_optional' => '',
			'status_required' => 'pending',
		];
		$entity = $this->Table->newEntity($data);

		$this->Table->saveOrFail($entity);

		$expected = [
			'status_optional' => null,
			'status_required' => 'pending',
		];
		$result = $entity->toArray();
		$this->assertSame($expected['status_optional'], $result['status_optional']);
		$this->assertSame($expected['status_required'], $result['status_required']);
	}

}
