<?php

namespace Shim\Database\Type;

use Cake\Core\Configure;
use Cake\Database\Type\JsonType as CakeJsonType;

/**
 * Allow setting json_encode options for JsonType through
 * global Configure key `Shim.jsonEncodingOptions`.
 */
class JsonType extends CakeJsonType {

	/**
	 * @param string|null $name
	 */
	public function __construct(?string $name = null) {
		parent::__construct($name);

		$encodingOptions = Configure::read('Shim.jsonEncodingOptions');
		if ($encodingOptions !== null) {
			$this->_encodingOptions = $encodingOptions;
		}
	}

}
