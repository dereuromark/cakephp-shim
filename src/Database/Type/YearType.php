<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use PDO;

/**
 * Experimental year type (MySQL)
 *
 * Needs:
 * - Type::map('year', 'Shim\Database\Type\YearType'); in bootstrap
 * - Manual FormHelper $this->Form->control('published', ['type' => 'year']);
 */
class YearType extends BaseType {

	/**
	 * Date format for DateTime object
	 *
	 * @var string
	 */
	protected $_format = 'Y';

	/**
	 * Convert binary data into the database format.
	 *
	 * Binary data is not altered before being inserted into the database.
	 * As PDO will handle reading file handles.
	 *
	 * @param int|string|array|null $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return int|null
	 */
	public function toDatabase($value, Driver $driver) {
		if (is_array($value)) {
			$value = $value['year'];
		}
		if ($value === null || !(int)$value) {
			return null;
		}
		return $value;
	}

	/**
	 * Convert binary into resource handles
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
	 * Get the correct PDO binding type for Year data.
	 *
	 * @param mixed $value The value being bound.
	 * @param \Cake\Database\Driver $driver The driver.
	 * @return int
	 */
	public function toStatement($value, Driver $driver) {
		return PDO::PARAM_INT;
	}

	/**
	 * Marshalls flat data into PHP objects.
	 *
	 * Most useful for converting request data into PHP objects,
	 * that make sense for the rest of the ORM/Database layers.
	 *
	 * @param mixed $value The value to convert.
	 * @return mixed Converted value.
	 */
	public function marshal($value) {
		if ($value === null || !(int)$value) {
			return null;
		}
		return $value;
	}

}
