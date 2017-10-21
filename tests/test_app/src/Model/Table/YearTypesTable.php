<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchema;
use Shim\Model\Table\Table;

class YearTypesTable extends Table {

	/**
	 * @var array
	 */
	public $order = ['name' => 'ASC'];

	/**
	 * @param \Cake\Database\Schema\TableSchema $schema
	 *
	 * @return \Cake\Database\Schema\TableSchema
	 */
	protected function _initializeSchema(TableSchema $schema) {
		$schema->setColumnType('year_of_birth', 'year');

		return $schema;
	}

}
