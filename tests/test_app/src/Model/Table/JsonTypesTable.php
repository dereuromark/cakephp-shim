<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Shim\Model\Table\Table;

class JsonTypesTable extends Table {

	/**
	 * @var array
	 */
	protected $order = ['name' => 'ASC'];

	/**
	 * @param \Cake\Database\Schema\TableSchemaInterface $schema
	 *
	 * @return \Cake\Database\Schema\TableSchemaInterface
	 */
	protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface {
		$schema->setColumnType('data', 'json');
		$schema->setColumnType('data_required', 'json');

		return $schema;
	}

}
