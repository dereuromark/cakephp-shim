<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class WheelsTable extends Table {

	public $displayField = 'position';

	public $order = ['position' => 'ASC'];

	public $actsAs = ['Useless'];

	public $validate = [
		'position' => [
			'notBlank' => [
				'rule' => 'notEmpty', // old way of notBlank
				'message' => 'Please insert sth',
				'allowEmpty' => false,
				'required' => false,
				'last' => true,
			],
			'maxLength' => [
				'rule' => ['maxLength', 20],
				'message' => ['valErrMaxCharacters {0} {1}', 'xyz', 20], // testing i18n
				'allowEmpty' => false,
				'last' => true,
			],
		]
	];

	public $belongsTo = [
		'Car' => [
			'className' => 'Car'
		]
	];

	// Bogus - for testing only
	public $hasAndBelongsToMany = [
		'HABTMCar' => [
			'className' => 'Car'
		]
	];

	// Bogus - for testing only
	public $hasOne = [
		'BogusCar' => [
			'className' => 'Car'
		]
	];

}
