<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchema;
use Shim\Model\Table\Table;

class TimeTypesTable extends Table {

	/**
	 * @param \Cake\Database\Schema\TableSchema $schema
	 *
	 * @return \Cake\Database\Schema\TableSchema
	 */
	protected function _initializeSchema(TableSchema $schema) {
		$schema->setColumnType('closing_time', 'time');

		return $schema;
	}

}
