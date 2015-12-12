<?php
App::uses('AppHelper', 'View/Helper');

/**
 * Helps migrating to 3.x
 *
 * Adds:
 * - Url->build()
 * - Url->webroot()
 * - Url->assetUrl()
 * - Url->assetTimestamp()
 * for View layer
 *
 * @author Mark Scherer
 * @license MIT
 */
class UrlShimHelper extends AppHelper {

	/**
	 * Returns a URL based on provided parameters.
	 *
	 * @param string|array|null $url Either a relative string url like `/products/view/23` or
	 *    an array of URL parameters. Using an array for URLs will allow you to leverage
	 *    the reverse routing features of CakePHP.
	 * @param bool $full If true, the full base URL will be prepended to the result
	 * @return string Full translated URL with base path.
	 */
	public function build($url = null, $full = false) {
		return $this->url($url, $full);
	}

	/**
	 * Returns a URL based on provided parameters.
	 *
	 * @param string|array|null $url Either a relative string url like `/products/view/23` or
	 *    an array of URL parameters. Using an array for URLs will allow you to leverage
	 *    the reverse routing features of CakePHP.
	 * @param bool $full If true, the full base URL will be prepended to the result
	 * @return string Full translated URL with base path.
	 */
	public function url($url = null, $full = false) {
		if (is_array($url)) {
			if (Configure::read('Shim.warnAboutOldRouting')) {
				if (isset($url['ext'])) {
					trigger_error('Param `ext` should be `_ext` in URL arrays.', E_USER_DEPRECATED);
				}
				if (isset($url['full_base'])) {
					trigger_error('Param `full_base` should be `_full` in URL arrays.', E_USER_DEPRECATED);
				}
			}
			if (isset($url['_ext'])) {
				$url['ext'] = $url['_ext'];
				unset($url['_ext']);
			}
			if (isset($url['_full'])) {
				$url['full_base'] = $url['_full'];
				unset($url['_full']);
			}
		}

		return parent::url($url, $full);
	}

}
