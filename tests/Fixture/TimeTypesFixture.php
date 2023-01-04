<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TimeTypesFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public array $fields = [
		'id' => ['type' => 'integer'],
		'name' => ['type' => 'string', 'null' => true],
		'closing_time' => ['type' => 'string', 'length' => 8, 'null' => true],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
	];

	/**
	 * Records property
	 *
	 * @var array
	 */
	public array $records = [
		[
			'name' => 'Some room',
			'closing_time' => '20:00:00',
		],
	];

}
