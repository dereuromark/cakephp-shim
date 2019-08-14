<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoolTypesFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'required' => ['type' => 'boolean', 'null' => false],
		'optional' => ['type' => 'boolean', 'null' => true],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
	];

	/**
	 * Records property
	 *
	 * @var array
	 */
	public $records = [
		[
			'required' => false,
			'optional' => null,
		],
	];

}
