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
	public function link($title, $url = null, $options = [], $confirmMessage = false) {
		if ($confirmMessage !== false) {
			trigger_error('$confirmMessage argument is deprecated as of 2.6. Use `confirm` key in $options instead.', E_USER_DEPRECATED);
		}
		return parent::link($title, $url, $options, $confirmMessage);
	}

	/**
	 * Creates a link element for CSS stylesheets.
	 *
	 * ### Options
	 *
	 * - `inline` If set to false, the generated tag will be appended to the 'css' block,
	 *   and included in the `$scripts_for_layout` layout variable. Defaults to true.
	 * - `once` Whether or not the css file should be checked for uniqueness. If true css
	 *   files  will only be included once, use false to allow the same
	 *   css to be included more than once per request.
	 * - `block` Set the name of the block link/style tag will be appended to.
	 *   This overrides the `inline` option.
	 * - `plugin` False value will prevent parsing path as a plugin
	 * - `rel` Defaults to 'stylesheet'. If equal to 'import' the stylesheet will be imported.
	 * - `fullBase` If true the URL will get a full address for the css file.
	 *
	 * @param string|array $path The name of a CSS style sheet or an array containing names of
	 *   CSS stylesheets. If `$path` is prefixed with '/', the path will be relative to the webroot
	 *   of your application. Otherwise, the path will be relative to your CSS path, usually webroot/css.
	 * @param array $options Array of options and HTML arguments.
	 * @return string CSS <link /> or <style /> tag, depending on the type of link.
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::css
	 */
	public function css($path, $options = []) {
		if (!is_array($options)) {
			$rel = $options;
			$options = [];
			if ($rel) {
				$options['rel'] = $rel;
			}
			if (func_num_args() > 2) {
				$options = func_get_arg(2) + $options;
			}
			unset($rel);

			trigger_error('The second argument needs to be an array. Use `rel` key in $options instead.', E_USER_DEPRECATED);
		}
		return parent::css($path, $options);
	}

}
