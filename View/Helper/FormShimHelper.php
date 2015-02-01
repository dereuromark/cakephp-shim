<?php
App::uses('FormHelper', 'View/Helper');

/**
 * Helps migrating to 3.x
 *
 * Some fixes:
 * - Reports if end() contains content
 * - ...
 *
 * @author Mark Scherer
 * @license MIT
 */
class FormShimHelper extends FormHelper {

	/**
	 * @return string
	 */
	public function end($options = null, $secureAttributes = array()) {
		if ($options !== null) {
			trigger_error('Please use submit() or alike to output buttons. end() is deprecated for this.', E_USER_DEPRECATED);
		}
		return parent::end($options, $secureAttributes);
	}

}
