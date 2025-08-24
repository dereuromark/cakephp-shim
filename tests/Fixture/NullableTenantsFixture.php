<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class NullableTenantsFixture extends TestFixture {

	/**
	 * @var array
	 */
	public array $fields = [
		'id' => ['type' => 'integer'],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
	];

	/**
	 * @var array
	 */
	public array $records = [];

}
