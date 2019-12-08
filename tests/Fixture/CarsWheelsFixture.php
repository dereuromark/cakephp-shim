<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoleFixture
 */
class CarsWheelsFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'car_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 11, 'collate' => null, 'comment' => ''],
		'wheel_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 11, 'collate' => null, 'comment' => ''],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
	];

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = [
		[
			'car_id' => 1,
			'wheel_id' => 1,
		],
	];

}
