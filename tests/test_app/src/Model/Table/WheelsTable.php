<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class WheelsTable extends Table {

	/**
	 * @var string
	 */
	protected $displayField = 'position';

	/**
	 * @var array
	 */
	protected $order = ['position' => 'ASC'];

	/**
	 * @var bool
	 */
	protected $createdField = false;

	/**
	 * @var bool
	 */
	protected $modifiedField = false;

	/**
	 * @var array
	 */
	protected $validate = [
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
		],
	];

	/**
	 * @var array
	 */
	protected $belongsTo = [
		'Car' => [
			'className' => 'Car',
		],
	];

	/**
	 * Bogus - for testing only
	 *
	 * @var array
	 */
	protected $hasAndBelongsToMany = [
		'HABTMCar' => [
			'className' => 'Car',
		],
	];

	/**
	 * Bogus - for testing only
	 *
	 * @var array
	 */
	protected $hasOne = [
		'BogusCar' => [
			'className' => 'Car',
		],
	];

}
