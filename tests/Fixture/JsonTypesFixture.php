<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 */
class JsonTypesFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'name' => ['type' => 'string', 'null' => true],
		'data' => ['type' => 'text', 'null' => true],
		'data_required' => ['type' => 'text', 'null' => false],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
	];

	/**
	 * Records property
	 *
	 * @var array
	 */
	public $records = [
		[
			'name' => 'Some person',
			'data' => null,
			'data_required' => 'null'
		],
	];

}
