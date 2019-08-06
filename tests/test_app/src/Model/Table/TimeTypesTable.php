<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Shim\Model\Table\Table;

class TimeTypesTable extends Table {

	/**
	 * @param \Cake\Database\Schema\TableSchemaInterface $schema
	 *
	 * @return \Cake\Database\Schema\TableSchemaInterface
	 */
	protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface {
		$schema->setColumnType('closing_time', 'time');

		return $schema;
	}

}
