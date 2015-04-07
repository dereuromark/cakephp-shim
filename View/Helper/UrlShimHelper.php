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
	 * Creates an HTML link.
	 *
	 * ### Options
	 *
	 * - `escape` Set to false to disable escaping of title and attributes.
	 * - `escapeTitle` Set to false to disable escaping of title. (Takes precedence over value of `escape`)
	 * - `confirm` JavaScript confirmation message.
	 *
	 * @param string $title The content to be wrapped by <a> tags.
	 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
	 * @param array $options Array of options and HTML attributes.
	 * @param string $confirmMessage JavaScript confirmation message. This
	 *   argument is deprecated as of 2.6. Use `confirm` key in $options instead.
	 * @return string An `<a />` element.
	 */
	public function build($url = null, $full = false) {
		return parent::url($url, $full);
	}

}
