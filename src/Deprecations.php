<?php

namespace Shim;

use Cake\Core\Configure;

class Deprecations {

	/**
	 * @param string $type
	 * @return bool
	 */
	public static function enabled($type) {
		$specificOn = Configure::read('Shim.deprecations.' . $type);

		if ($specificOn === true || $specificOn === false) {
			return $specificOn;
		}

		$globalOn = Configure::read('Shim.deprecations') === true;

		return $globalOn;
	}

	/**
	 * @param string $message
	 * @return void
	 */
	public static function error($message) {
		$type = Configure::read('Shim.deprecationType') ?: E_USER_DEPRECATED;

		trigger_error($message, $type);
	}

}
