<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class NullablesFixture extends TestFixture {

	/**
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'optional_id' => ['type' => 'integer', 'null' => true],
		'required_id' => ['type' => 'integer', 'null' => false],
		'string_optional' => ['type' => 'string', 'null' => true],
		'string_required' => ['type' => 'string', 'null' => false],
		'string_optional_notnull' => ['type' => 'string', 'null' => true, 'default' => ''],
		'active_optional' => ['type' => 'boolean', 'null' => true],
		'active_required' => ['type' => 'boolean', 'null' => false],
		'active_optional_notnull' => ['type' => 'boolean', 'null' => true, 'default' => 0],
		'datetime_optional' => ['type' => 'datetime', 'null' => true],
		'datetime_required' => ['type' => 'datetime', 'null' => false],
		'nullable_tenant_id' => ['type' => 'integer', 'null' => true],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
	];

	/**
	 * @var array
	 */
	public $records = [
	];

}
