<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\JsonType as CoreJsonType;

/**
 * Does not convert null values to JSON string "null". Respects the nullable part of a table field.
 *
 * Make sure your db field is default null allowing then if you want it to be optional.
 * This is mainly for MySQL. Postgres etc can use their native implementations.
 *
 * Needs:
 * - Type::map('json', 'Shim\Database\Type\JsonType'); in bootstrap
 * - Run `UPDATE table_name SET field_name = null WHERE field_name = 'null'` to clean up the table
 */
class JsonType extends CoreJsonType {

	/**
	 * @param mixed $value
	 * @param \Cake\Database\Driver $driver
	 *
	 * @return string|null
	 */
	public function toDatabase($value, Driver $driver) {
		if ($value === null) {
			return null;
		}

		return parent::toDatabase($value, $driver);
	}

}
