<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Shim\Model\Table\Table;

class YearTypesTable extends Table {

	/**
	 * @var array
	 */
	public $order = ['name' => 'ASC'];

	/**
	 * @param \Cake\Database\Schema\TableSchemaInterface $schema
	 *
	 * @return \Cake\Database\Schema\TableSchemaInterface
	 */
	protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface {
		$schema->setColumnType('year_of_birth', 'year');

		return $schema;
	}

}
