<?php

namespace Tools\Test\TestCase\Model\Behavior;

use Cake\Database\Type;
use Cake\Database\Type\BoolType as CoreBoolType;
use Cake\Database\Type\StringType as CoreStringType;
use Cake\ORM\TableRegistry;
use Shim\Database\Type\BoolType;
use Shim\Database\Type\StringType;
use Shim\TestSuite\TestCase;

/**
 * With StringType and BoolType from Shim plugin behavior is a bit different now (as per 4.x behavior).
 */
class NullableBehaviorShimmedTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Shim.Nullables',
		'plugin.Shim.NullableTenants',
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

		Type::map('string', StringType::class);
		Type::map('boolean', BoolType::class);

		$this->Table = TableRegistry::get('Nullables');
		$this->Table->addAssociations(['hasOne' => ['NullableTenants' => ['hasMany' => 'Nullables']]]);
		$this->Table->addBehavior('Shim.Nullable');
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		Type::map('string', CoreStringType::class);
		Type::map('boolean', CoreBoolType::class);

		TableRegistry::clear();
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
			'active_required' => null, // !
			'datetime_optional' => null,
			'datetime_required' => null,
			'nullable_tenant' => null,
		];
		$this->assertSame($expected, $entity->toArray());
	}

}
