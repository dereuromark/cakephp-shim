<?php
App::uses('HtmlHelper', 'View/Helper');

/**
 * Helps migrating to 3.x
 *
 * Some fixes:
 * - Reports if $confirmMessage argument is still used (CakePHP2.6+).
 * - Notifies if 
 *
 * @author Mark Scherer
 * @license MIT
 */
class HtmlShimHelper extends HtmlHelper {

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
	public function link($title, $url = null, $options = array(), $confirmMessage = false) {
		if ($confirmMessage !== false) {
			trigger_error('$confirmMessage argument is deprecated as of 2.6. Use `confirm` key in $options instead.', E_USER_DEPRECATED);
		}
		return parent::link($title, $url, $options, $confirmMessage);
	}

}
