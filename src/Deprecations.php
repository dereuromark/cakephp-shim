<?php

namespace Shim;

use Cake\Core\Configure;

class Deprecations {

	/**
	 * @param string $type
	 * @return bool
	 */
	public static function enabled(string $type): bool {
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
	public static function error(string $message): void {
		$type = Configure::read('Shim.deprecationType') ?: E_USER_DEPRECATED;

		trigger_error($message, $type);
	}

}
