<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class YearTypesFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'name' => ['type' => 'string', 'null' => true],
		'year_of_birth' => ['type' => 'integer', 'null' => true],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
	];

	/**
	 * Records property
	 *
	 * @var array
	 */
	public $records = [
		[
			'name' => 'Some person',
			'year_of_birth' => 2015,
		],
	];

}
