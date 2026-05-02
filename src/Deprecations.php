<?php
declare(strict_types=1);

namespace Shim;

use Cake\Core\Configure;

class Deprecations {

	/**
	 * Levels accepted by `trigger_error()` for the `error_type` argument.
	 * Anything else raises a `ValueError` on PHP 8+.
	 *
	 * @var array<int>
	 */
	protected const VALID_TRIGGER_LEVELS = [
		E_USER_NOTICE,
		E_USER_WARNING,
		E_USER_DEPRECATED,
		E_USER_ERROR,
	];

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
		if (!in_array($type, static::VALID_TRIGGER_LEVELS, true)) {
			$type = E_USER_DEPRECATED;
		}

		trigger_error($message, $type);
	}

}
