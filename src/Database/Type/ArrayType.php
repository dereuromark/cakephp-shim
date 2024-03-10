<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;

/**
 * Do not convert array/string input on marshal(). Useful for Array handling behaviors.
 */
class ArrayType extends BaseType {

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function marshal(mixed $value): mixed {
		if ($value !== null && !is_array($value) && !is_string($value)) {
			return null;
		}

		return $value;
	}

	/**
	 * Casts given value from a PHP type to one acceptable by a database.
	 *
	 * @param mixed $value Value to be converted to a database equivalent.
	 * @param \Cake\Database\Driver $driver Object from which database preferences and configuration will be extracted.
	 * @return mixed Given PHP type casted to one acceptable by a database.
	 */
	public function toDatabase(mixed $value, Driver $driver): mixed {
		if ($value !== null && !is_string($value)) {
			return null;
		}

		return $value;
	}

	/**
	 * Casts given value from a database type to a PHP equivalent.
	 *
	 * @param mixed $value Value to be converted to PHP equivalent
	 * @param \Cake\Database\Driver $driver Object from which database preferences and configuration will be extracted
	 * @return mixed Given value casted from a database to a PHP equivalent.
	 */
	public function toPHP(mixed $value, Driver $driver): mixed {
		if ($value !== null && !is_array($value) && !is_string($value)) {
			return null;
		}

		return $value;
	}

}
