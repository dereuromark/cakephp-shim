<?php

/**
 * Exception raised when a particular record was not found
 */
class RecordNotFoundException extends CakeException {

	/**
	 * Constructor.
	 *
	 * @param string $message The error message.
	 * @param int $code The code of the error, is also the HTTP status code for the error.
	 */
	public function __construct($message, $code = 404) {
		parent::__construct($message, $code);
	}

}
