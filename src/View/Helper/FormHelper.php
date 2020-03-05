<?php
declare(strict_types = 1);
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Shim\View\Helper;

use Cake\View\Helper\FormHelper as CoreFormHelper;
use Cake\View\View;
use DateTime;

/**
 * Form helper which retains CakePHP 3.x style selects for datetime fields.
 */
class FormHelper extends CoreFormHelper {

	/**
	 * The various pickers that make up a datetime picker.
	 *
	 * @var array
	 */
	protected $_datetimeParts = ['year', 'month', 'day', 'hour', 'minute', 'second', 'meridian'];

	/**
	 * Special options used for datetime inputs.
	 *
	 * @var array
	 */
	protected $_datetimeOptions = [
		'interval', 'round', 'monthNames', 'minYear', 'maxYear',
		'orderYear', 'timeFormat', 'second',
	];

	/**
	 * Grouped input types.
	 *
	 * @var array
	 */
	protected $_groupedInputTypes = ['radio', 'multicheckbox', 'date', 'time', 'datetime'];

	/**
	 * Construct the widgets and binds the default context providers
	 *
	 * @param \Cake\View\View $view The View this helper is being attached to.
	 * @param array $config Configuration settings for the helper.
	 */
	public function __construct(View $view, array $config = []) {
		$this->_defaultConfig['templates']['dateWidget'] = '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}';
		$this->_defaultWidgets['datetime'] = ['Shim.DateTime', 'select'];

		parent::__construct($view, $config);
	}

	/**
	 * Helper method for the various single datetime component methods.
	 *
	 * @param array $options The options array.
	 * @param string $keep The option to not disable.
	 * @return array
	 */
	protected function _singleDatetime(array $options, string $keep): array {
		$off = array_diff($this->_datetimeParts, [$keep]);
		$off = (array)array_combine(
			$off,
			array_fill(0, count($off), false)
		);

		$attributes = array_diff_key(
			$options,
			array_flip(array_merge($this->_datetimeOptions, ['value', 'empty']))
		);

		$options = $options + $off + [$keep => $attributes];
		if (isset($options['value'])) {
			$options['val'] = $options['value'];
		}

		return $options;
	}

	/**
	 * Returns a SELECT element for days.
	 *
	 * ### Options:
	 *
	 * - `empty` - If true, the empty select option is shown. If a string,
	 *   that string is displayed as the empty element.
	 * - `value` The selected value of the input.
	 *
	 * @param string|null $fieldName Prefix name for the SELECT element
	 * @param array $options Options & HTML attributes for the select element
	 * @return string A generated day select box.
	 * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-day-inputs
	 */
	public function day(?string $fieldName = null, array $options = []): string {
		$options = $this->_singleDatetime($options, 'day');

		if (isset($options['val']) && $options['val'] > 0 && $options['val'] <= 31) {
			$options['val'] = [
				'year' => date('Y'),
				'month' => date('m'),
				'day' => (int)$options['val'],
			];
		}

		return $this->dateTime($fieldName, $options);
	}

	/**
	 * Returns a SELECT element for years
	 *
	 * ### Attributes:
	 *
	 * - `empty` - If true, the empty select option is shown. If a string,
	 *   that string is displayed as the empty element.
	 * - `orderYear` - Ordering of year values in select options.
	 *   Possible values 'asc', 'desc'. Default 'desc'
	 * - `value` The selected value of the input.
	 * - `maxYear` The max year to appear in the select element.
	 * - `minYear` The min year to appear in the select element.
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options Options & attributes for the select elements.
	 * @return string Completed year select input
	 * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-year-inputs
	 */
	public function year(string $fieldName, array $options = []): string {
		$options = $this->_singleDatetime($options, 'year');

		$len = isset($options['val']) ? strlen($options['val']) : 0;
		if (isset($options['val']) && $len > 0 && $len < 5) {
			$options['val'] = [
				'year' => (int)$options['val'],
				'month' => date('m'),
				'day' => date('d'),
			];
		}

		return $this->dateTime($fieldName, $options);
	}

	/**
	 * Returns a SELECT element for months.
	 *
	 * ### Options:
	 *
	 * - `monthNames` - If false, 2 digit numbers will be used instead of text.
	 *   If an array, the given array will be used.
	 * - `empty` - If true, the empty select option is shown. If a string,
	 *   that string is displayed as the empty element.
	 * - `value` The selected value of the input.
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options Attributes for the select element
	 * @return string A generated month select dropdown.
	 * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-month-inputs
	 */
	public function month(string $fieldName, array $options = []): string {
		$options = $this->_singleDatetime($options, 'month');

		if (isset($options['val']) && $options['val'] > 0 && $options['val'] <= 12) {
			$options['val'] = [
				'year' => date('Y'),
				'month' => (int)$options['val'],
				'day' => date('d'),
			];
		}

		return $this->dateTime($fieldName, $options);
	}

	/**
	 * Returns a SELECT element for hours.
	 *
	 * ### Attributes:
	 *
	 * - `empty` - If true, the empty select option is shown. If a string,
	 *   that string is displayed as the empty element.
	 * - `value` The selected value of the input.
	 * - `format` Set to 12 or 24 to use 12 or 24 hour formatting. Defaults to 24.
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options List of HTML attributes
	 * @return string Completed hour select input
	 * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-hour-inputs
	 */
	public function hour(string $fieldName, array $options = []): string {
		$options += ['format' => 24];
		$options = $this->_singleDatetime($options, 'hour');

		$options['timeFormat'] = $options['format'];
		unset($options['format']);

		if (isset($options['val']) && $options['val'] > 0 && $options['val'] <= 24) {
			$options['val'] = [
				'hour' => (int)$options['val'],
				'minute' => date('i'),
			];
		}

		return $this->dateTime($fieldName, $options);
	}

	/**
	 * Returns a SELECT element for minutes.
	 *
	 * ### Attributes:
	 *
	 * - `empty` - If true, the empty select option is shown. If a string,
	 *   that string is displayed as the empty element.
	 * - `value` The selected value of the input.
	 * - `interval` The interval that minute options should be created at.
	 * - `round` How you want the value rounded when it does not fit neatly into an
	 *   interval. Accepts 'up', 'down', and null.
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options Array of options.
	 * @return string Completed minute select input.
	 * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-minute-inputs
	 */
	public function minute(string $fieldName, array $options = []): string {
		$options = $this->_singleDatetime($options, 'minute');

		if (isset($options['val']) && $options['val'] > 0 && $options['val'] <= 60) {
			$options['val'] = [
				'hour' => date('H'),
				'minute' => (int)$options['val'],
			];
		}

		return $this->dateTime($fieldName, $options);
	}

	/**
	 * Returns a SELECT element for AM or PM.
	 *
	 * ### Attributes:
	 *
	 * - `empty` - If true, the empty select option is shown. If a string,
	 *   that string is displayed as the empty element.
	 * - `value` The selected value of the input.
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options Array of options
	 * @return string Completed meridian select input
	 * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-meridian-inputs
	 */
	public function meridian(string $fieldName, array $options = []): string {
		$options = $this->_singleDatetime($options, 'meridian');

		if (isset($options['val'])) {
			$hour = date('H');
			$options['val'] = [
				'hour' => $hour,
				'minute' => (int)$options['val'],
				'meridian' => $hour > 11 ? 'pm' : 'am',
			];
		}

		return $this->dateTime($fieldName, $options);
	}

	/**
	 * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
	 *
	 * ### Date Options:
	 *
	 * - `empty` - If true, the empty select option is shown. If a string,
	 *   that string is displayed as the empty element.
	 * - `value`|`default` The default value to be used by the input. A value in ` $this->data`
	 *   matching the field name will override this value. If no default is provided `time()` will be used.
	 * - `monthNames` If false, 2 digit numbers will be used instead of text.
	 *   If an array, the given array will be used.
	 * - `minYear` The lowest year to use in the year select
	 * - `maxYear` The maximum year to use in the year select
	 * - `orderYear` - Order of year values in select options.
	 *   Possible values 'asc', 'desc'. Default 'desc'.
	 *
	 * ### Time options:
	 *
	 * - `empty` - If true, the empty select option is shown. If a string,
	 * - `value`|`default` The default value to be used by the input. A value in ` $this->data`
	 *   matching the field name will override this value. If no default is provided `time()` will be used.
	 * - `timeFormat` The time format to use, either 12 or 24.
	 * - `interval` The interval for the minutes select. Defaults to 1
	 * - `round` - Set to `up` or `down` if you want to force rounding in either direction. Defaults to null.
	 * - `second` Set to true to enable seconds drop down.
	 *
	 * To control the order of inputs, and any elements/content between the inputs you
	 * can override the `dateWidget` template. By default the `dateWidget` template is:
	 *
	 * `{{month}}{{day}}{{year}}{{hour}}{{minute}}{{second}}{{meridian}}`
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options Array of Options
	 * @return string Generated set of select boxes for the date and time formats chosen.
	 * @link https://book.cakephp.org/3.0/en/views/helpers/form.html#creating-date-and-time-inputs
	 */
	public function dateTime(string $fieldName, array $options = []): string {
		$options += [
			'empty' => true,
			'value' => null,
			'interval' => 1,
			'round' => null,
			'monthNames' => true,
			'minYear' => null,
			'maxYear' => null,
			'orderYear' => 'desc',
			'timeFormat' => 24,
			'second' => false,
		];
		$options = $this->_initInputField($fieldName, $options);
		$options = $this->_datetimeOptions($options);

		return $this->widget('datetime', $options);
	}

	/**
	 * Helper method for converting from FormHelper options data to widget format.
	 *
	 * @param array $options Options to convert.
	 * @return array Converted options.
	 */
	protected function _datetimeOptions(array $options): array {
		foreach ($this->_datetimeParts as $type) {
			if (!array_key_exists($type, $options)) {
				$options[$type] = [];
			}
			if ($options[$type] === true) {
				$options[$type] = [];
			}

			// Pass empty options to each type.
			if (!empty($options['empty']) &&
				is_array($options[$type])
			) {
				$options[$type]['empty'] = $options['empty'];
			}

			// Move empty options into each type array.
			if (isset($options['empty'][$type])) {
				$options[$type]['empty'] = $options['empty'][$type];
			}
			if (isset($options['required']) && is_array($options[$type])) {
				$options[$type]['required'] = $options['required'];
			}
		}

		$hasYear = is_array($options['year']);
		if ($hasYear && isset($options['minYear'])) {
			$options['year']['start'] = $options['minYear'];
		}
		if ($hasYear && isset($options['maxYear'])) {
			$options['year']['end'] = $options['maxYear'];
		}
		if ($hasYear && isset($options['orderYear'])) {
			$options['year']['order'] = $options['orderYear'];
		}
		unset($options['minYear'], $options['maxYear'], $options['orderYear']);

		if (is_array($options['month'])) {
			$options['month']['names'] = $options['monthNames'];
		}
		unset($options['monthNames']);

		if (is_array($options['hour']) && isset($options['timeFormat'])) {
			$options['hour']['format'] = $options['timeFormat'];
		}
		unset($options['timeFormat']);

		if (is_array($options['minute'])) {
			$options['minute']['interval'] = $options['interval'];
			$options['minute']['round'] = $options['round'];
		}
		unset($options['interval'], $options['round']);

		if ($options['val'] === true || $options['val'] === null
			&& isset($options['empty'])
			&& $options['empty'] === false
		) {
			$val = new DateTime();
			$currentYear = $val->format('Y');
			if (isset($options['year']['end']) && $options['year']['end'] < $currentYear) {
				$val->setDate((int)$options['year']['end'], (int)$val->format('n'), (int)$val->format('j'));
			}
			$options['val'] = $val;
		}

		unset($options['empty']);

		return $options;
	}

	/**
	 * Generate time inputs.
	 *
	 * ### Options:
	 *
	 * See dateTime() for time options.
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options Array of Options
	 * @return string Generated set of select boxes for time formats chosen.
	 * @see \Cake\View\Helper\FormHelper::dateTime() for templating options.
	 */
	public function time(string $fieldName, array $options = []): string {
		$options += [
			'empty' => true,
			'value' => null,
			'interval' => 1,
			'round' => null,
			'timeFormat' => 24,
			'second' => false,
		];
		$options['year'] = $options['month'] = $options['day'] = false;
		$options = $this->_initInputField($fieldName, $options);
		$options = $this->_datetimeOptions($options);

		return $this->widget('datetime', $options);
	}

	/**
	 * Generate date inputs.
	 *
	 * ### Options:
	 *
	 * See dateTime() for date options.
	 *
	 * @param string $fieldName Prefix name for the SELECT element
	 * @param array $options Array of Options
	 * @return string Generated set of select boxes for time formats chosen.
	 * @see \Cake\View\Helper\FormHelper::dateTime() for templating options.
	 */
	public function date(string $fieldName, array $options = []): string {
		$options += [
			'empty' => true,
			'value' => null,
			'monthNames' => true,
			'minYear' => null,
			'maxYear' => null,
			'orderYear' => 'desc',
		];
		$options['hour'] = $options['minute'] = false;
		$options['meridian'] = $options['second'] = false;

		$options = $this->_initInputField($fieldName, $options);
		$options = $this->_datetimeOptions($options);

		return $this->widget('datetime', $options);
	}

}
