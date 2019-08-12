<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\Database\TypeInterface;
use PDO;

/**
 * Experimental year type (MySQL)
 *
 * Needs:
 * - Type::map('year', 'Shim\Database\Type\YearType'); in bootstrap
 * - Manual FormHelper $this->Form->control('published', ['type' => 'year']);
 */
class YearType extends Type implements TypeInterface {

	/**
	 * Date format for DateTime object
	 *
	 * @var string
	 */
	protected $_format = 'Y';

	/**
	 * Converts year data into the database format.
	 *
	 * @param int|string|array|null $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return int|null
	 */
	public function toDatabase($value, Driver $driver) {
		return $this->marshal($value);
	}

	/**
	 * Converts DB year column into PHP int.
	 *
	 * @param null|string|resource $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return int|null
	 * @throws \Cake\Core\Exception\Exception
	 */
	public function toPHP($value, Driver $driver) {
		if ($value === null || !(int)$value) {
			return null;
		}
		return (int)$value;
	}

	/**
	 * Converts year data into the database format.
	 *
	 * @param int|string|array|null $value
	 * @return int|null
	 */
	public function marshal($value)
	{
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
	public function toStatement($value, Driver $driver) {
		return PDO::PARAM_INT;
	}

}
