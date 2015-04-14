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
	 * @param string|array $url Either a relative string url like `/products/view/23` or
	 *    an array of URL parameters. Using an array for URLs will allow you to leverage
	 *    the reverse routing features of CakePHP.
	 * @param bool $full If true, the full base URL will be prepended to the result
	 * @return string Full translated URL with base path.
	 */
	public function build($url = null, $full = false) {
		return parent::url($url, $full);
	}

}
