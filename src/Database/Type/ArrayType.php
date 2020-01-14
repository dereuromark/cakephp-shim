<?php

namespace Shim\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\Type\BaseType;

/**
 * Do not convert array/string input on marshal(). Useful for Array handling behaviors.
 *
 * @see Tools.Jsonable behavior
 */
class ArrayType extends BaseType {

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function marshal($value) {
		if ($value !== null && !is_array($value) && !is_string($value)) {
			return null;
		}

		return $value;
	}

	/**
	 * Casts given value from a PHP type to one acceptable by a database.
	 *
	 * @param mixed $value Value to be converted to a database equivalent.
	 * @param \Cake\Database\DriverInterface $driver Object from which database preferences and configuration will be extracted.
	 * @return mixed Given PHP type casted to one acceptable by a database.
	 */
	public function toDatabase($value, DriverInterface $driver) {
		if ($value !== null && !is_string($value)) {
			return null;
		}

		return $value;
	}

	/**
	 * Casts given value from a database type to a PHP equivalent.
	 *
	 * @param mixed $value Value to be converted to PHP equivalent
	 * @param \Cake\Database\DriverInterface $driver Object from which database preferences and configuration will be extracted
	 * @return mixed Given value casted from a database to a PHP equivalent.
	 */
	public function toPHP($value, DriverInterface $driver) {
		if ($value !== null && !is_array($value) && !is_string($value)) {
			return null;
		}

		return $value;
	}

}
