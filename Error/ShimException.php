<?php

/**
 * Represents a shim error
 */
class ShimException extends CakeException {

	/**
	 * @param string $message The error message.
	 * @param int $code The error code.
	 * @param string|null $details Details.
	 */
	public function __construct($message, $code = 500, $details = null) {
		parent::__construct($message, $code);
		if ($details) {
			Configure::write('Exception.details', $details);
		}
	}

}
