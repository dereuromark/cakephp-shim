<?php
namespace Shim\View\Helper;

use Cake\View\Helper\UrlHelper as CoreUrlHelper;

/**
 * Use this for better 4.x safety as this can alert you about deprecations.
 */
class UrlHelper extends CoreUrlHelper {

	/**
	 * Returns a URL based on provided parameters.
	 *
	 * ### Options:
	 *
	 * - `escape`: If false, the URL will be returned unescaped, do only use if it is manually
	 *    escaped afterwards before being displayed.
	 * - `fullBase`: If true, the full base URL will be prepended to the result
	 *
	 * @param string|array|null $url Either a relative string URL like `/products/view/23` or
	 *    an array of URL parameters. Using an array for URLs will allow you to leverage
	 *    the reverse routing features of CakePHP.
	 * @param array|bool $options Array of options; bool `full` for BC reasons (deprecated).
	 * @return string Full translated URL with base path.
	 */
	public function build($url = null, $options = false) {
		if (!is_array($options)) {
			trigger_error('The bool $options part is deprecated. Use an array here instead with `\'fullBase\' => true`.', E_USER_DEPRECATED);
		}

		return parent::build($url, $options);
	}

}
