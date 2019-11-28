<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoleFixture
 */
class CarsFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'model' => ['type' => 'string', 'null' => false, 'length' => 255, 'comment' => '', 'charset' => 'utf8'],
		'created' => 'datetime',
		'updated' => 'datetime',
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
	];

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = [
		[
			'model' => 'Audi',
			'created' => '2007-03-18 10:55:23',
			'updated' => '2007-03-18 10:57:31',
		],
	];

}
