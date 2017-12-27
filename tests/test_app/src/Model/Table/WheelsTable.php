<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class WheelsTable extends Table {

	/**
	 * @var string
	 */
	public $displayField = 'position';

	/**
	 * @var array
	 */
	public $order = ['position' => 'ASC'];

	/**
	 * @var bool
	 */
	public $createdField = false;

	/**
	 * @var bool
	 */
	public $modifiedField = false;

	/**
	 * @var array
	 */
	public $validate = [
		'car_id' => [
			'numeric',
		],
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

	/**
	 * @var array
	 */
	public $belongsTo = [
		'Car' => [
			'className' => 'Car'
		]
	];

	/**
	 * Bogus - for testing only
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = [
		'HABTMCar' => [
			'className' => 'Car'
		]
	];

	/**
	 * Bogus - for testing only
	 *
	 * @var array
	 */
	public $hasOne = [
		'BogusCar' => [
			'className' => 'Car'
		]
	];

}
