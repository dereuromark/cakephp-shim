<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 */
class NullablesFixture extends TestFixture {

	/**
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'optional_id' => ['type' => 'integer', 'null' => true],
		'required_id' => ['type' => 'integer', 'null' => false],
		'comment' => 'text',
		'string_optional' => ['type' => 'text', 'null' => true],
		'string_required' => ['type' => 'text', 'null' => false],
		'active_optional' => ['type' => 'boolean', 'null' => true],
		'active_required' => ['type' => 'boolean', 'null' => false],
		'datetime_optional' => ['type' => 'datetime', 'null' => true],
		'datetime_required' => ['type' => 'datetime', 'null' => false],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
	];

	/**
	 * @var array
	 */
	public $records = [
	];

}
