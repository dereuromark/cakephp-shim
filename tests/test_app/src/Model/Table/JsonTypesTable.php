<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Shim\Model\Table\Table;

class JsonTypesTable extends Table {

	/**
	 * @var array<int|string, mixed>|string|null
	 */
	protected array $order = ['name' => 'ASC'];

	/**
	 * @return \Cake\Database\Schema\TableSchemaInterface
	 */
	public function getSchema(): TableSchemaInterface {
		$schema = parent::getSchema();
		$schema->setColumnType('data', 'json');
		$schema->setColumnType('data_required', 'json');

		return $schema;
	}

}
