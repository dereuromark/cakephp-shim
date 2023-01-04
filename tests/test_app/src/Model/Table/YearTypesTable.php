<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Shim\Model\Table\Table;

class YearTypesTable extends Table {

	/**
	 * @var array<int|string, mixed>|string|null
	 */
	protected $order = ['name' => 'ASC'];

	/**
	 * @return \Cake\Database\Schema\TableSchemaInterface
	 */
	public function getSchema(): TableSchemaInterface {
		$schema = parent::getSchema();
		$schema->setColumnType('year_of_birth', 'year');

		return $schema;
	}

}
