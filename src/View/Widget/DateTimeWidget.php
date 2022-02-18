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
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link https://cakephp.org CakePHP(tm) Project
 * @since 3.0.0
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Shim\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use Cake\View\Widget\SelectBoxWidget;
use Cake\View\Widget\WidgetInterface;
use DateTime;
use Exception;
use RuntimeException;

/**
 * Input widget class for generating a date time input widget.
 *
 * This class is intended as an internal implementation detail
 * of Cake\View\Helper\FormHelper and is not intended for direct use.
 */
class DateTimeWidget implements WidgetInterface {

	/**
	 * Select box widget.
	 *
	 * @var \Cake\View\Widget\SelectBoxWidget
	 */
	protected $_select;

	/**
	 * List of inputs that can be rendered
	 *
	 * @var array<string>
	 */
	protected $_selects = [
		'year',
		'month',
		'day',
		'hour',
		'minute',
		'second',
		'meridian',
	];

	/**
	 * Template instance.
	 *
	 * @var \Cake\View\StringTemplate
	 */
	protected $_templates;

	/**
	 * Constructor
	 *
	 * @param \Cake\View\StringTemplate $templates Templates list.
	 * @param \Cake\View\Widget\SelectBoxWidget $selectBox Selectbox widget instance.
	 */
	public function __construct(StringTemplate $templates, SelectBoxWidget $selectBox) {
		$this->_select = $selectBox;
		$this->_templates = $templates;
	}

	/**
	 * Renders a date time widget
	 *
	 * - `name` - Set the input name.
	 * - `disabled` - Either true or an array of options to disable.
	 * - `val` - A date time string, integer or DateTime object
	 * - `empty` - Set to true to add an empty option at the top of the
	 *   option elements. Set to a string to define the display value of the
	 *   empty option.
	 *
	 * In addition to the above options, the following options allow you to control
	 * which input elements are generated. By setting any option to false you can disable
	 * that input picker. In addition each picker allows you to set additional options
	 * that are set as HTML properties on the picker.
	 *
	 * - `year` - Array of options for the year select box.
	 * - `month` - Array of options for the month select box.
	 * - `day` - Array of options for the day select box.
	 * - `hour` - Array of options for the hour select box.
	 * - `minute` - Array of options for the minute select box.
	 * - `second` - Set to true to enable the seconds input. Defaults to false.
	 * - `meridian` - Set to true to enable the meridian input. Defaults to false.
	 *   The meridian will be enabled automatically if you choose a 12 hour format.
	 *
	 * The `year` option accepts the `start` and `end` options. These let you control
	 * the year range that is generated. It defaults to +-5 years from today.
	 *
	 * The `month` option accepts the `name` option which allows you to get month
	 * names instead of month numbers.
	 *
	 * The `hour` option allows you to set the following options:
	 *
	 * - `format` option which accepts 12 or 24, allowing
	 *   you to indicate which hour format you want.
	 * - `start` The hour to start the options at.
	 * - `end` The hour to stop the options at.
	 *
	 * The start and end options are dependent on the format used. If the
	 * value is out of the start/end range it will not be included.
	 *
	 * The `minute` option allows you to define the following options:
	 *
	 * - `interval` The interval to round options to.
	 * - `round` Accepts `up` or `down`. Defines which direction the current value
	 *   should be rounded to match the select options.
	 *
	 * @param array<string, mixed> $data Data to render with.
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @throws \RuntimeException When option data is invalid.
	 * @return string A generated select box.
	 */
	public function render(array $data, ContextInterface $context): string {
		$data = $this->_normalizeData($data);

		$selected = $this->_deconstructDate($data['val'], $data);

		$templateOptions = ['templateVars' => $data['templateVars']];
		foreach ($this->_selects as $select) {
			if ($data[$select] === false || $data[$select] === null) {
				$templateOptions[$select] = '';
				unset($data[$select]);

				continue;
			}
			if (!is_array($data[$select])) {
				throw new RuntimeException(sprintf(
					'Options for "%s" must be an array|false|null',
					$select,
				));
			}
			$method = "_{$select}Select";
			$data[$select]['name'] = $data['name'] . '[' . $select . ']';
			$data[$select]['val'] = $selected[$select];

			if (!isset($data[$select]['empty'])) {
				$data[$select]['empty'] = $data['empty'];
			}
			if (!isset($data[$select]['disabled'])) {
				$data[$select]['disabled'] = $data['disabled'];
			}
			if (isset($data[$select]['templateVars']) && $templateOptions['templateVars']) {
				$data[$select]['templateVars'] = array_merge(
					$templateOptions['templateVars'],
					$data[$select]['templateVars'],
				);
			}
			if (!isset($data[$select]['templateVars'])) {
				$data[$select]['templateVars'] = $templateOptions['templateVars'];
			}
			if ($select === 'year' && isset($data['minYear'])) {
				$data[$select]['start'] = $data['minYear'];
			}
			if ($select === 'year' && isset($data['maxYear'])) {
				$data[$select]['end'] = $data['maxYear'];
			}
			if ($select === 'year' && isset($data['orderYear'])) {
				$data[$select]['order'] = $data['orderYear'];
			}
			if (isset($data['class'], $data[$select]['class'])) {
				$classes = $this->_templates->addClass($data[$select]['class'], $data['class']);
				$data[$select]['class'] = join($classes['class'], ' ');
			} else if (isset($data['class'])) {
				$data[$select]['class'] = $data['class'];
			}

			$templateOptions[$select] = $this->{$method}($data[$select], $context);
			unset($data[$select]);
		}
		unset($data['name'], $data['empty'], $data['disabled'], $data['val']);
		$templateOptions['attrs'] = $this->_templates->formatAttributes($data);

		return $this->_templates->format('dateWidget', $templateOptions);
	}

	/**
	 * Normalize data.
	 *
	 * @param array<string, mixed> $data Data to normalize.
	 * @return array<string, mixed> Normalized data.
	 */
	protected function _normalizeData(array $data): array {
		$data += [
			'name' => '',
			'empty' => false,
			'disabled' => null,
			'val' => null,
			'year' => [],
			'month' => [],
			'day' => [],
			'hour' => [],
			'minute' => [],
			'second' => [],
			'meridian' => null,
			'templateVars' => [],
		];

		$timeFormat = $data['hour']['format'] ?? null;
		if ($timeFormat === 12 && !isset($data['meridian'])) {
			$data['meridian'] = [];
		}
		if ($timeFormat === 24) {
			$data['meridian'] = false;
		}

		// When using widget standalone
		$monthNames = $data['monthNames'] ?? null;
		if ($monthNames !== null && is_array($data['month'])) {
			$data['month']['names'] = $monthNames;
		}
		unset($data['monthNames']);

		$interval = $data['interval'] ?? null;
		if ($interval) {
			$data['minute']['interval'] = $interval;
		}
		unset($data['interval']);

		return $data;
	}

	/**
	 * Deconstructs the passed date value into all time units
	 *
	 * @param \DateTime|array|string|int|bool|null $value Value to deconstruct.
	 * @param array<string, mixed> $options Options for conversion.
	 * @return array<string, string>
	 */
	protected function _deconstructDate($value, array $options): array {
		if ($value === '' || $value === null) {
			return [
				'year' => '', 'month' => '', 'day' => '',
				'hour' => '', 'minute' => '', 'second' => '',
				'meridian' => '',
			];
		}
		try {
			if (is_string($value) && !is_numeric($value)) {
				$date = new DateTime($value);
			} elseif (is_bool($value)) {
				$date = new DateTime();
			} elseif (is_int($value) || is_numeric($value)) {
				$date = new DateTime('@' . $value);
			} elseif (is_array($value)) {
				$dateArray = [
					'year' => '', 'month' => '', 'day' => '',
					'hour' => '', 'minute' => '', 'second' => '',
					'meridian' => '',
				];
				$validDate = false;
				foreach ($dateArray as $key => $dateValue) {
					$exists = isset($value[$key]);
					if ($exists) {
						$validDate = true;
					}
					if ($exists && $value[$key] !== '') {
						$dateArray[$key] = str_pad((string)$value[$key], 2, '0', STR_PAD_LEFT);
					}
				}
				if ($validDate) {
					if (!$dateArray['second']) {
						$dateArray['second'] = '0';
					}
					if (!empty($value['meridian'])) {
						/** @var string $meridian */
						$meridian = $dateArray['meridian'];
						$isAm = strtolower($meridian) === 'am';
						$dateArray['hour'] = $isAm ? (int)$dateArray['hour'] : (int)$dateArray['hour'] + 12;
						$dateArray['hour'] = str_pad((string)$dateArray['hour'], 2, '0', STR_PAD_LEFT);
					}
					if (!empty($dateArray['minute']) && isset($options['minute']['interval'])) {
						$dateArray['minute'] += $this->_adjustValue((int)$dateArray['minute'], $options['minute']);
						$dateArray['minute'] = str_pad((string)$dateArray['minute'], 2, '0', STR_PAD_LEFT);
					}

					return $dateArray;
				}

				$date = new DateTime();
			} else {
				/** @var \DateTime $value */
				$date = clone $value;
			}
		} catch (Exception $e) {
			$date = new DateTime();
		}

		if (isset($options['minute']['interval'])) {
			$change = $this->_adjustValue((int)$date->format('i'), $options['minute']);
			$date->modify($change > 0 ? "+$change minutes" : "$change minutes");
		}

		return [
			'year' => $date->format('Y'),
			'month' => $date->format('m'),
			'day' => $date->format('d'),
			'hour' => $date->format('H'),
			'minute' => $date->format('i'),
			'second' => $date->format('s'),
			'meridian' => $date->format('a'),
		];
	}

	/**
	 * Adjust $value based on rounding settings.
	 *
	 * @param int $value The value to adjust.
	 * @param array<string, mixed> $options The options containing interval and possibly round.
	 * @return int The amount to adjust $value by.
	 */
	protected function _adjustValue(int $value, array $options): int {
		$options += ['interval' => 1, 'round' => null];
		$changeValue = $value * (1 / $options['interval']);
		switch ($options['round']) {
			case 'up':
				$changeValue = ceil($changeValue);

	            break;
			case 'down':
				$changeValue = floor($changeValue);

	            break;
			default:
				$changeValue = round($changeValue);
		}

		return (int)($changeValue * $options['interval']) - $value;
	}

	/**
	 * Generates a year select
	 *
	 * @param array<string, mixed> $options Options list.
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @return string
	 */
	protected function _yearSelect(array $options, ContextInterface $context): string {
		$options += [
			'name' => '',
			'val' => null,
			'start' => date('Y', strtotime('-5 years')),
			'end' => date('Y', strtotime('+5 years')),
			'order' => 'desc',
			'templateVars' => [],
			'options' => [],
		];

		if (!empty($options['val'])) {
			$options['start'] = min($options['val'], $options['start']);
			$options['end'] = max($options['val'], $options['end']);
		}
		if (empty($options['options'])) {
			$options['options'] = $this->_generateNumbers((int)$options['start'], (int)$options['end']);
		}
		if ($options['order'] === 'desc') {
			$options['options'] = array_reverse($options['options'], true);
		}
		unset($options['start'], $options['end'], $options['order']);

		return $this->_select->render($options, $context);
	}

	/**
	 * Generates a month select
	 *
	 * @param array<string, mixed> $options The options to build the month select with
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @return string
	 */
	protected function _monthSelect(array $options, ContextInterface $context): string {
		$options += [
			'name' => '',
			'names' => false,
			'val' => null,
			'leadingZeroKey' => true,
			'leadingZeroValue' => false,
			'templateVars' => [],
		];

		if (empty($options['options'])) {
			if ($options['names'] === true) {
				$options['options'] = $this->_getMonthNames($options['leadingZeroKey']);
			} elseif (is_array($options['names'])) {
				$options['options'] = $options['names'];
			} else {
				$options['options'] = $this->_generateNumbers(1, 12, $options);
			}
		}

		unset($options['leadingZeroKey'], $options['leadingZeroValue'], $options['names']);

		return $this->_select->render($options, $context);
	}

	/**
	 * Generates a day select
	 *
	 * @param array<string, mixed> $options The options to generate a day select with.
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @return string
	 */
	protected function _daySelect(array $options, ContextInterface $context): string {
		$options += [
			'name' => '',
			'val' => null,
			'leadingZeroKey' => true,
			'leadingZeroValue' => false,
			'templateVars' => [],
		];
		$options['options'] = $this->_generateNumbers(1, 31, $options);

		unset($options['names'], $options['leadingZeroKey'], $options['leadingZeroValue']);

		return $this->_select->render($options, $context);
	}

	/**
	 * Generates a hour select
	 *
	 * @param array<string, mixed> $options The options to generate an hour select with
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @return string
	 */
	protected function _hourSelect(array $options, ContextInterface $context): string {
		$options += [
			'name' => '',
			'val' => null,
			'format' => 24,
			'start' => null,
			'end' => null,
			'leadingZeroKey' => true,
			'leadingZeroValue' => false,
			'templateVars' => [],
		];
		$is24 = $options['format'] === 24;

		$defaultStart = $is24 ? 0 : 1;
		$defaultEnd = $is24 ? 23 : 12;
		$options['start'] = max($defaultStart, $options['start']);

		$options['end'] = min($defaultEnd, $options['end']);
		if ($options['end'] === null) {
			$options['end'] = $defaultEnd;
		}

		if (!$is24 && $options['val'] > 12) {
			$options['val'] = sprintf('%02d', $options['val'] - 12);
		}
		if (!$is24 && in_array($options['val'], ['00', '0', 0], true)) {
			$options['val'] = 12;
		}

		if (empty($options['options'])) {
			$options['options'] = $this->_generateNumbers(
				$options['start'],
				$options['end'],
				$options,
			);
		}

		unset(
			$options['end'],
			$options['start'],
			$options['format'],
			$options['leadingZeroKey'],
			$options['leadingZeroValue'],
		);

		return $this->_select->render($options, $context);
	}

	/**
	 * Generates a minute select
	 *
	 * @param array<string, mixed> $options The options to generate a minute select with.
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @return string
	 */
	protected function _minuteSelect(array $options, ContextInterface $context): string {
		$options += [
			'name' => '',
			'val' => null,
			'interval' => 1,
			'round' => 'up',
			'leadingZeroKey' => true,
			'leadingZeroValue' => true,
			'templateVars' => [],
		];
		$options['interval'] = max($options['interval'], 1);
		if (empty($options['options'])) {
			$options['options'] = $this->_generateNumbers(0, 59, $options);
		}

		unset(
			$options['leadingZeroKey'],
			$options['leadingZeroValue'],
			$options['interval'],
			$options['round'],
		);

		return $this->_select->render($options, $context);
	}

	/**
	 * Generates a second select
	 *
	 * @param array<string, mixed> $options The options to generate a second select with
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @return string
	 */
	protected function _secondSelect(array $options, ContextInterface $context): string {
		$options += [
			'name' => '',
			'val' => null,
			'leadingZeroKey' => true,
			'leadingZeroValue' => true,
			'options' => $this->_generateNumbers(0, 59),
			'templateVars' => [],
		];

		unset($options['leadingZeroKey'], $options['leadingZeroValue']);

		return $this->_select->render($options, $context);
	}

	/**
	 * Generates a meridian select
	 *
	 * @param array<string, mixed> $options The options to generate a meridian select with.
	 * @param \Cake\View\Form\ContextInterface $context The current form context.
	 * @return string
	 */
	protected function _meridianSelect(array $options, ContextInterface $context): string {
		$options += [
			'name' => '',
			'val' => null,
			'options' => ['am' => 'am', 'pm' => 'pm'],
			'templateVars' => [],
		];

		return $this->_select->render($options, $context);
	}

	/**
	 * Returns a translated list of month names
	 *
	 * @param bool $leadingZero Whether to generate month keys with leading zero.
	 * @return array<string>
	 */
	protected function _getMonthNames(bool $leadingZero = false): array {
		$months = [
			'01' => __d('cake', 'January'),
			'02' => __d('cake', 'February'),
			'03' => __d('cake', 'March'),
			'04' => __d('cake', 'April'),
			'05' => __d('cake', 'May'),
			'06' => __d('cake', 'June'),
			'07' => __d('cake', 'July'),
			'08' => __d('cake', 'August'),
			'09' => __d('cake', 'September'),
			'10' => __d('cake', 'October'),
			'11' => __d('cake', 'November'),
			'12' => __d('cake', 'December'),
		];

		if ($leadingZero === false) {
			$i = 1;
			foreach ($months as $key => $name) {
				unset($months[$key]);
				$months[$i++] = $name;
			}
		}

		return $months;
	}

	/**
	 * Generates a range of numbers
	 *
	 * ### Options
	 *
	 * - leadingZeroKey - Set to true to add a leading 0 to single digit keys.
	 * - leadingZeroValue - Set to true to add a leading 0 to single digit values.
	 * - interval - The interval to generate numbers for. Defaults to 1.
	 *
	 * @param int $start Start of the range of numbers to generate
	 * @param int $end End of the range of numbers to generate
	 * @param array<string, mixed> $options Options list.
	 * @return array<string, string>
	 */
	protected function _generateNumbers(int $start, int $end, array $options = []): array {
		$options += [
			'leadingZeroKey' => true,
			'leadingZeroValue' => true,
			'interval' => 1,
		];

		$numbers = [];
		$i = $start;
		while ($i <= $end) {
			$key = (string)$i;
			$value = (string)$i;
			if ($options['leadingZeroKey'] === true) {
				$key = sprintf('%02d', $key);
			}
			if ($options['leadingZeroValue'] === true) {
				$value = sprintf('%02d', $value);
			}
			$numbers[$key] = $value;
			$i += $options['interval'];
		}

		return $numbers;
	}

	/**
	 * Returns a list of fields that need to be secured for this widget.
	 *
	 * When the hour picker is in 24hr mode (null or format=24) the meridian
	 * picker will be omitted.
	 *
	 * @param array<string, mixed> $data The data to render.
	 * @return array<string> Array of fields to secure.
	 */
	public function secureFields(array $data): array {
		$data = $this->_normalizeData($data);

		$fields = [];
		foreach ($this->_selects as $select) {
			if ($data[$select] === false || $data[$select] === null) {
				continue;
			}

			$fields[] = $data['name'] . '[' . $select . ']';
		}

		return $fields;
	}

}
