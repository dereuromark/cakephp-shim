<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class CarsTable extends Table {

	protected array $actsAs = ['Useless'];

	/**
	 * @var array
	 */
	protected array $hasMany = [
		'Wheel' => [
			'className' => 'Wheel',
		],
	];

}
