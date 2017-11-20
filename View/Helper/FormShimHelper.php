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
	 * Returns an HTML FORM element.
	 *
	 * ### Options:
	 *
	 * - `type` Form method defaults to POST
	 * - `action`  The controller action the form submits to, (optional).
	 * - `url`  The URL the form submits to. Can be a string or a URL array. If you use 'url'
	 *    you should leave 'action' undefined.
	 * - `default`  Allows for the creation of Ajax forms. Set this to false to prevent the default event handler.
	 *   Will create an onsubmit attribute if it doesn't not exist. If it does, default action suppression
	 *   will be appended.
	 * - `onsubmit` Used in conjunction with 'default' to create ajax forms.
	 * - `inputDefaults` set the default $options for FormHelper::input(). Any options that would
	 *   be set when using FormHelper::input() can be set here. Options set with `inputDefaults`
	 *   can be overridden when calling input()
	 * - `encoding` Set the accept-charset encoding for the form. Defaults to `Configure::read('App.encoding')`
	 *
	 * @param mixed $model The model name for which the form is being defined. Should
	 *   include the plugin name for plugin models. e.g. `ContactManager.Contact`.
	 *   If an array is passed and $options argument is empty, the array will be used as options.
	 *   If `false` no model is used.
	 * @param array $options An array of html attributes and options.
	 * @return string A formatted opening FORM tag.
	 */
	public function create($model = null, $options = []) {
		if (isset($options['action'])) {
			trigger_error('Using key `action` is deprecated, use `url` directly instead.', E_USER_DEPRECATED);
		}

		return parent::create($model, $options);
	}

	/**
	 * Creates an HTML link, but access the URL using the method you specify (defaults to POST).
	 * Requires javascript to be enabled in browser.
	 *
	 * This method creates a `<form>` element. So do not use this method inside an existing form.
	 * Instead you should add a submit button using FormHelper::submit()
	 *
	 * ### Options:
	 *
	 * - `data` - Array with key/value to pass in input hidden
	 * - `method` - Request method to use. Set to 'delete' to simulate HTTP/1.1 DELETE request. Defaults to 'post'.
	 * - `confirm` - Can be used instead of $confirmMessage.
	 * - `inline` - Whether or not the associated form tag should be output inline.
	 *   Set to false to have the form tag appended to the 'postLink' view block.
	 *   Defaults to true.
	 * - `block` - Choose a custom block to append the form tag to. Using this option
	 *   will override the inline option.
	 * - Other options are the same of HtmlHelper::link() method.
	 * - The option `onclick` will be replaced.
	 *
	 * @param string $title The content to be wrapped by <a> tags.
	 * @param string|array|null $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
	 * @param array $options Array of HTML attributes.
	 * @param bool|string $confirmMessage JavaScript confirmation message. This
	 *   argument is deprecated as of 2.6. Use `confirm` key in $options instead.
	 * @return string An `<a />` element.
	 */
	 public function postLink($title, $url = null, $options = [], $confirmMessage = false) {
		if ($confirmMessage !== false) {
			trigger_error('$confirmMessage argument is deprecated as of 2.6. Use `confirm` key in $options instead.', E_USER_DEPRECATED);
		}
		return parent::postLink($title, $url, $options, $confirmMessage);
	}

	/**
	 * Closes an HTML form, cleans up values set by FormHelper::create(), and writes hidden
	 * input fields where appropriate.
	 *
	 * If $options is set a form submit button will be created. Options can be either a string or an array.
	 *
	 * If $secureAttributes is set, these html attributes will be merged into the hidden input tags generated for the
	 * Security Component. This is especially useful to set HTML5 attributes like 'form'
	 *
	 * @param string|array|null $options as a string will use $options as the value of button,
	 * @param array $secureAttributes will be passed as html attributes into the hidden input elements generated for the
	 *   Security Component.
	 * @return string a closing FORM tag optional submit button.
	 */
	public function end($options = null, $secureAttributes = []) {
		if ($options !== null) {
			trigger_error('Please use submit() or alike to output buttons. end() is deprecated for this.', E_USER_DEPRECATED);
		}
		return parent::end($options, $secureAttributes);
	}

}
