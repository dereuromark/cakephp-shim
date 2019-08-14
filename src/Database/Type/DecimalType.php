<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\DecimalType as CoreDecimalType;

/**
 * This is a FC shim (4.x behavior in 3.x). Will be not necessary anymore in 4.x.
 */
class DecimalType extends CoreDecimalType {

	/**
	 * Marshals request data into decimal strings.
	 *
	 * Arrays are casted to null instead of empty string.
	 *
	 * @param mixed $value The value to convert.
	 * @return string|null Converted value.
	 */
	public function marshal($value) {
		if ($value === null || $value === '') {
			return null;
		}
		if (is_string($value) && $this->_useLocaleParser) {
			return $this->_parseValue($value);
		}
		if (is_numeric($value)) {
			return (string)$value;
		}
		if (is_string($value) && preg_match('/^[0-9,. ]+$/', $value)) {
			return $value;
		}

		return null;
	}

	/**
	 * Convert float values to PHP strings.
	 *
	 * @param mixed $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return string|null
	 */
	public function toPHP($value, Driver $driver) {
		if ($value === null) {
			return $value;
		}

		return (string)$value;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return string[]
	 */
	public function manyToPHP(array $values, array $fields, Driver $driver) {
		foreach ($fields as $field) {
			if (!isset($values[$field])) {
				continue;
			}

			$values[$field] = (string)$values[$field];
		}

		return $values;
	}

	/**
	 * Converts a string into a float point after parsing it using the locale
	 * aware parser.
	 *
	 * @param string $value The value to parse and convert to an float.
	 * @return string
	 */
	protected function _parseValue($value) {
		/** @var \Cake\I18n\Number $class */
		$class = static::$numberClass;

		return (string)$class::parseFloat($value);
	}

}
