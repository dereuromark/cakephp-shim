<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\JsonType as CoreJsonType;

/**
 * Does not convert null values to json string "null".
 *
 * Make sure your db field is default null allowing then.
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
