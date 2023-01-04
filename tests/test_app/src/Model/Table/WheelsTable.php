<?php

namespace TestApp\Model\Table;

use Shim\Model\Table\Table;

class WheelsTable extends Table {

	protected string $displayField = 'position';

	/**
	 * @var array<int|string, mixed>|string|null
	 */
	protected $order = ['position' => 'ASC'];

	/**
	 * @var string|false
	 */
	protected $createdField = false;

	/**
	 * @var string|false
	 */
	protected $modifiedField = false;

	/**
	 * @var array
	 */
	protected array $validate = [
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
	protected array $belongsTo = [
		'Car' => [
			'className' => 'Car',
		],
	];

	/**
	 * Bogus - for testing only
	 *
	 * @var array
	 */
	protected array $hasAndBelongsToMany = [
		'HABTMCar' => [
			'className' => 'Car',
		],
	];

	/**
	 * Bogus - for testing only
	 *
	 * @var array
	 */
	protected array $hasOne = [
		'BogusCar' => [
			'className' => 'Car',
		],
	];

}
