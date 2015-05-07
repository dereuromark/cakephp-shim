<?php
namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoleFixture
 *
 */
class WheelsFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'position' => ['type' => 'string', 'null' => false, 'length' => 255, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'charset' => 'utf8'],
		'car_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 11, 'collate' => null, 'comment' => ''],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
	];

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = [
		[
			'id' => '1',
			'position' => 'front left',
			'car_id' => 1
		],
		[
			'id' => '2',
			'position' => 'rear right',
			'car_id' => 1
		]
	];

}
