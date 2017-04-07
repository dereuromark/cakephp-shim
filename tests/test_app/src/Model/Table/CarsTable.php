<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class CarsTable extends Table {

	/**
	 * @var array
	 */
	public $actsAs = ['Useless'];

	/**
	 * @var array
	 */
	public $hasMany = [
		'Wheel' => [
			'className' => 'Wheel'
		]
	];

}
