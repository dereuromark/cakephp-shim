<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use PDO;

/**
 * Experimental year type (MySQL)
 *
 * Needs:
 * - \Cake\Database\TypeFactory::map('year'), 'Shim\Database\Type\YearType'); in bootstrap
 * - Manual FormHelper $this->Form->control('published', ['type' => 'year']);
 */
class YearType extends BaseType {

	/**
	 * Date format for DateTime object
	 */
	protected string $_format = 'Y';

	/**
	 * Converts year data into the database format.
	 *
	 * @param array|string|int|null $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return int|null
	 */
	public function toDatabase($value, Driver $driver): ?int {
		if (is_array($value)) {
			$value = $value['year'];
		}
		if ($value === null || !(int)$value) {
			return null;
		}

		return $value;
	}

	/**
	 * Converts DB year column into PHP int.
	 *
	 * @param resource|string|null $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return int|null
	 */
	public function toPHP($value, Driver $driver): ?int {
		if ($value === null || !(int)$value) {
			return null;
		}

		return (int)$value;
	}

	/**
	 * Converts year data into the database format.
	 *
	 * @param array|string|int|null $value
	 * @return int|null
	 */
	public function marshal($value): ?int {
		if (is_array($value)) {
			$value = $value['year'];
		}
		if ($value === null || !(int)$value) {
			return null;
		}

		return $value;
	}

	/**
	 * Get the correct PDO binding type for Year data.
	 *
	 * @param mixed $value The value being bound.
	 * @param \Cake\Database\Driver $driver The driver.
	 * @return int
	 */
	public function toStatement(mixed $value, Driver $driver): int {
		return PDO::PARAM_INT;
	}

}
