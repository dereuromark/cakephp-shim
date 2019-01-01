<?php

namespace Tools\Test\TestCase\Model\Behavior;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Shim\TestSuite\TestCase;

class NullableBehaviorTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Shim.Nullables',
		'plugin.Shim.NullableTenants'
	];

	/**
	 * @var \Shim\Model\Table\Table|\Shim\Model\Behavior\NullableBehavior
	 */
	public $Table;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->Table = TableRegistry::get('Nullables');
		$this->Table->addAssociations(['hasOne' => ['NullableTenants' => ['hasMany' => 'Nullables']]]);
		$this->Table->addBehavior('Shim.Nullable');
	}

	/**
	 * @return void
	 */
	public function testPatch() {
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
			'active_required' => false,
			'datetime_optional' => null,
			'datetime_required' => null,
			'nullable_tenant' => null,
		];
		$this->assertSame($expected, $entity->toArray());
	}

	/**
	 * @return void
	 */
	public function testPatchAssociation() {
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
			'active_required' => false,
			'datetime_optional' => null,
			'datetime_required' => null,
			'tenant' => ['id' => 1],
		];
		$this->assertSame($expected, $entity->toArray());
	}

	/**
	 * @return void
	 */
	public function testPatchOptionalNotNull() {
		$this->skipIf(version_compare(Configure::version(), '3.3.7') <= 0);

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

}
