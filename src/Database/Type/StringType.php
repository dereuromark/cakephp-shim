<?php

namespace Shim\Database\Type;

use Cake\Database\Type\StringType as CoreStringType;

/**
 * This is a FC shim (4.x behavior in 3.x). Will be not necessary anymore in 4.x.
 */
class StringType extends CoreStringType {

	/**
	 * Marshals request data into PHP strings.
	 *
	 * Arrays are casted to null instead of empty string.
	 *
	 * @param mixed $value The value to convert.
	 * @return string|null Converted value.
	 */
	public function marshal($value) {
		if (is_array($value)) {
			return null;
		}

		return parent::marshal($value);
	}

}
