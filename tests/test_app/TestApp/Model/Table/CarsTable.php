<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class CarsTable extends Table {

	public $hasMany = [
		'Wheel' => [
			'className' => 'Wheel'
		]
	];

}
