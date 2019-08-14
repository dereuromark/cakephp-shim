<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DecimalTypesFixture extends TestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'required' => ['type' => 'decimal', 'null' => false],
		'optional' => ['type' => 'decimal', 'null' => true],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
	];

	/**
	 * Records property
	 *
	 * @var array
	 */
	public $records = [
		[
			'required' => '1.1',
			'optional' => null,
		],
	];

}
