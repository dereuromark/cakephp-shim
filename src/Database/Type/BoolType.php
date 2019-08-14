<?php

namespace Shim\Database\Type;

use Cake\Database\Type\BoolType as CoreBoolType;

/**
 * This is a FC shim (4.x behavior in 3.x). Will be not necessary anymore in 4.x.
 */
class BoolType extends CoreBoolType {

	/**
	 * Marshals request data into PHP booleans.
	 *
	 * Non-boolean string values are converted to null now.
	 *
	 * @param mixed $value The value to convert.
	 * @return bool|null Converted value.
	 */
	public function marshal($value) {
		if ($value === null || $value === '') {
			return null;
		}

		return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}

}
