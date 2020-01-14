<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class CarsTable extends Table {

	/**
	 * @var array
	 */
	protected $actsAs = ['Useless'];

	/**
	 * @var array
	 */
	protected $hasMany = [
		'Wheel' => [
			'className' => 'Wheel',
		],
	];

}
