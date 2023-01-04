<?php

namespace Shim\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UuiditemFixture
 */
class UuidItemsFixture extends TestFixture {

	/**
	 * fields property
	 *
	 * @var array
	 */
	public array $fields = [
		'id' => ['type' => 'uuid'],
		'published' => ['type' => 'boolean', 'null' => false],
		'name' => ['type' => 'string', 'null' => false],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
	];

	/**
	 * records property
	 *
	 * @var array
	 */
	public array $records = [
		['id' => '481fc6d0-b920-43e0-a40d-6d1740cf8569', 'published' => 0, 'name' => 'Item 1'],
	];

}
