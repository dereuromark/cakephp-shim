<?php

namespace Shim;

use Cake\Core\Configure;

class Config {
	/**
	 * @param string $type
	 * @return bool
	 */
	public static function deprecations($type) {
		$specificOn = Configure::read('Shim.deprecations.' . $type);

		if ($specificOn === true || $specificOn === false) {
			return $specificOn;
		}

		$globalOn = Configure::read('Shim.deprecations') === true;

		return $globalOn;
	}

}
