<?php

namespace Tools\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Shim\TestSuite\TestCase;

class NullableBehaviorTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.shim.nullables'
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
		];
		$this->assertSame($expected, $entity->toArray());
	}

}
