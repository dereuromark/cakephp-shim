<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Shim\Model\Table\Table;

class YearTypesTable extends Table {

	/**
	 * @var array
	 */
	public $order = ['name' => 'ASC'];

	/**
	 * @param Schema $schema
	 *
	 * @return Schema
	 */
	protected function _initializeSchema(Schema $schema) {
		$schema->columnType('year_of_birth', 'year');
		return $schema;
	}

}
