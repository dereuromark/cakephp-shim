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

	/**
	 * Generates a form input element complete with label and wrapper div
	 *
	 * ### Options
	 *
	 * See each field type method for more information. Any options that are part of
	 * $attributes or $options for the different **type** methods can be included in `$options` for input().i
	 * Additionally, any unknown keys that are not in the list below, or part of the selected type's options
	 * will be treated as a regular html attribute for the generated input.
	 *
	 * - `type` - Force the type of widget you want. e.g. `type => 'select'`
	 * - `label` - Either a string label, or an array of options for the label. See FormHelper::label().
	 * - `div` - Either `false` to disable the div, or an array of options for the div.
	 *	See HtmlHelper::div() for more options.
	 * - `options` - For widgets that take options e.g. radio, select.
	 * - `error` - Control the error message that is produced. Set to `false` to disable any kind of error reporting (field
	 *    error and error messages).
	 * - `errorMessage` - Boolean to control rendering error messages (field error will still occur).
	 * - `empty` - String or boolean to enable empty select box options.
	 * - `before` - Content to place before the label + input.
	 * - `after` - Content to place after the label + input.
	 * - `between` - Content to place between the label + input.
	 * - `format` - Format template for element order. Any element that is not in the array, will not be in the output.
	 *	- Default input format order: array('before', 'label', 'between', 'input', 'after', 'error')
	 *	- Default checkbox format order: array('before', 'input', 'between', 'label', 'after', 'error')
	 *	- Hidden input will not be formatted
	 *	- Radio buttons cannot have the order of input and label elements controlled with these settings.
	 *
	 * @param string $fieldName This should be "Modelname.fieldname"
	 * @param array $options Each type of input takes different options.
	 * @return string Completed form widget.
	 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#creating-form-elements
	 */
	public function input($fieldName, $options = array()) {
		$message = 'FormHelper::input() is deprecated. Use FormHelper::control() instead.';
		Shim::check(Shim::FORM_INPUTS, $message);
		return parent::input($fieldName, $options);
	}

	protected function _checkDeprecatedInputOptions($optionKeys) {
		$supportedKeys = [
			'type',
			'label',
			'options',
			'error',
			'empty',
			'nestedInput',
			'templates',
			'labelOptions',
		];
		$diff = array_diff($optionKeys, $supportedKeys);
		if (!empty($diff)) {
			$unsupportedKeys = explode(', ', $diff);
			$message = "FormHelper::control() does not support $unsupportedKeys option(s).";
			Shim::check(Shim::FORM_INPUTS, $message);
		}
	}

	/**
	 * Generates a form control element complete with label and wrapper div.
	 *
	 * ### Options
	 *
	 * See each field type method for more information. Any options that are part of
	 * $attributes or $options for the different **type** methods can be included in `$options` for input().
	 * Additionally, any unknown keys that are not in the list below, or part of the selected type's options
	 * will be treated as a regular HTML attribute for the generated input.
	 *
	 * - `type` - Force the type of widget you want. e.g. `type => 'select'`
	 * - `label` - Either a string label, or an array of options for the label. See FormHelper::label().
	 * - `options` - For widgets that take options e.g. radio, select.
	 * - `error` - Control the error message that is produced. Set to `false` to disable any kind of error reporting (field
	 *    error and error messages).
	 * - `empty` - String or boolean to enable empty select box options.
	 * - `nestedInput` - Used with checkbox and radio inputs. Set to false to render inputs outside of label
	 *   elements. Can be set to true on any input to force the input inside the label. If you
	 *   enable this option for radio buttons you will also need to modify the default `radioWrapper` template.
	 * - `templates` - The templates you want to use for this input. Any templates will be merged on top of
	 *   the already loaded templates. This option can either be a filename in /config that contains
	 *   the templates you want to load, or an array of templates to use.
	 * - `labelOptions` - Either `false` to disable label around nestedWidgets e.g. radio, multicheckbox or an array
	 *   of attributes for the label tag. `selected` will be added to any classes e.g. `class => 'myclass'` where
	 *   widget is checked
	 *
	 * @param string $fieldName This should be "modelname.fieldname"
	 * @param array $options Each type of input takes different options.
	 * @return string Completed form widget.
	 * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-form-inputs
	 */
	public function control($fieldName, array $options = []) {
		$optionKeys = array_keys($options);
		$this->_checkDeprecatedInputOptions($optionKeys);
		return parent::input($fieldName, $options);
	}

	/**
	 * Generate a set of inputs for `$fields`. If $fields is null the fields of current model
	 * will be used.
	 *
	 * You can customize individual inputs through `$fields`.
	 * ```
	 *	$this->Form->inputs(array(
	 *		'name' => array('label' => 'custom label')
	 *	));
	 * ```
	 *
	 * In addition to controller fields output, `$fields` can be used to control legend
	 * and fieldset rendering.
	 * `$this->Form->inputs('My legend');` Would generate an input set with a custom legend.
	 * Passing `fieldset` and `legend` key in `$fields` array has been deprecated since 2.3,
	 * for more fine grained control use the `fieldset` and `legend` keys in `$options` param.
	 *
	 * @param array $fields An array of fields to generate inputs for, or null.
	 * @param array $blacklist A simple array of fields to not create inputs for.
	 * @param array $options Options array. Valid keys are:
	 * - `fieldset` Set to false to disable the fieldset. If a string is supplied it will be used as
	 *    the class name for the fieldset element.
	 * - `legend` Set to false to disable the legend for the generated input set. Or supply a string
	 *    to customize the legend text.
	 * @return string Completed form inputs.
	 * @link https://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::inputs
	 */
	public function inputs($fields = null, $blacklist = null, $options = array()) {
		$message = 'FormHelper::inputs() is deprecated. Use FormHelper::controls() instead.';
		Shim::check(Shim::FORM_INPUTS, $message);
		return parent::inputs($fields, $blacklist, $options);
	}

	/**
	 * Generate a set of controls for `$fields` wrapped in a fieldset element.
	 *
	 * You can customize individual controls through `$fields`.
	 * ```
	 * $this->Form->controls([
	 *   'name' => ['label' => 'custom label'],
	 *   'email'
	 * ]);
	 * ```
	 *
	 * @param array $fields An array of the fields to generate. This array allows
	 *   you to set custom types, labels, or other options.
	 * @param array $options Options array. Valid keys are:
	 * - `fieldset` Set to false to disable the fieldset. You can also pass an
	 *    array of params to be applied as HTML attributes to the fieldset tag.
	 *    If you pass an empty array, the fieldset will be enabled.
	 * - `legend` Set to false to disable the legend for the generated input set.
	 *    Or supply a string to customize the legend text.
	 * @return string Completed form inputs.
	 * @link https://book.cakephp.org/3/en/views/helpers/form.html#generating-entire-forms
	 */
	public function controls(array $fields, array $options = []) {
		$shim = Configure::read(Shim::FORM_INPUTS);
		if ($shim) {
			foreach ($fields as $fieldOptions) {
				$optionKeys = array_keys($fieldOptions);
				$this->_checkDeprecatedInputOptions($optionKeys);
			}
		}
		Configure::write(Shim::FORM_INPUTS, false);
		$result = parent::inputs($fields, null, $options);
		Configure::write(Shim::FORM_INPUTS, $shim);
		return $result;
	}

}
