<?php

namespace Shim\View\Helper;

use Cake\View\Helper\NumberHelper as CakeNumberHelper;

/**
 * Shim null/empty behavior for format() and currency()
 */
class NumberHelper extends CakeNumberHelper {

	/**
	 * Formats a number into the correct locale format
	 *
	 * Options:
	 *
	 * - `places` - Minimum number or decimals to use, e.g 0
	 * - `precision` - Maximum Number of decimal places to use, e.g. 2
	 * - `locale` - The locale name to use for formatting the number, e.g. fr_FR
	 * - `before` - The string to place before whole numbers, e.g. '['
	 * - `after` - The string to place after decimal numbers, e.g. ']'
	 * - `escape` - Whether to escape html in resulting string
	 * - `default` - The default value in case passed value is null
	 *
	 * @param string|float|int|null $number A floating point number.
	 * @param array<string, mixed> $options An array with options.
	 * @return string Formatted number
	 */
	public function format(string|float|int|null $number, array $options = []): string {
		if ($number === null || $number === '') {
			return $options['default'] ?? '';
		}

		return parent::format($number, $options);
	}

	/**
	 * Formats a number into a currency format.
	 *
	 * ### Options
	 *
	 * - `locale` - The locale name to use for formatting the number, e.g. fr_FR
	 * - `fractionSymbol` - The currency symbol to use for fractional numbers.
	 * - `fractionPosition` - The position the fraction symbol should be placed
	 *    valid options are 'before' & 'after'.
	 * - `before` - Text to display before the rendered number
	 * - `after` - Text to display after the rendered number
	 * - `zero` - The text to use for zero values, can be a string or a number. e.g. 0, 'Free!'
	 * - `places` - Number of decimal places to use. e.g. 2
	 * - `precision` - Maximum Number of decimal places to use, e.g. 2
	 * - `roundingMode` - Rounding mode to use. e.g. NumberFormatter::ROUND_HALF_UP.
	 *   When not set locale default will be used
	 * - `pattern` - An ICU number pattern to use for formatting the number. e.g #,##0.00
	 * - `useIntlCode` - Whether to replace the currency symbol with the international
	 *   currency code.
	 * - `escape` - Whether to escape html in resulting string
	 * - `default` - The default value in case passed value is null
	 *
	 * @param string|float|null $number Value to format.
	 * @param string|null $currency International currency name such as 'USD', 'EUR', 'JPY', 'CAD'
	 * @param array<string, mixed> $options Options list.
	 * @return string Number formatted as a currency.
	 */
	public function currency(string|float|null $number, ?string $currency = null, array $options = []): string {
		if ($number === null || $number === '') {
			return $options['default'] ?? '';
		}

		return parent::currency($number, $currency, $options);
	}

}
