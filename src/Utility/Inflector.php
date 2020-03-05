<?php
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
 * @since         0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Shim\Utility;

use Cake\Utility\Inflector as CoreInflector;

/**
 * 3.x shim/port of Inflector::slug() as Text::slug() is not fully BC in some cases.
 *
 * Pluralize and singularize English words.
 *
 * Inflector pluralizes and singularizes English nouns.
 * Used by CakePHP's naming conventions throughout the framework.
 *
 * @link https://book.cakephp.org/3.0/en/core-libraries/inflector.html
 */
class Inflector extends CoreInflector {

	/**
	 * Default map of accented and special characters to ASCII characters
	 *
	 * @var array
	 */
	protected static $_transliteration = [
		'ä' => 'ae',
		'æ' => 'ae',
		'ǽ' => 'ae',
		'ö' => 'oe',
		'œ' => 'oe',
		'ü' => 'ue',
		'Ä' => 'Ae',
		'Ü' => 'Ue',
		'Ö' => 'Oe',
		'À' => 'A',
		'Á' => 'A',
		'Â' => 'A',
		'Ã' => 'A',
		'Å' => 'A',
		'Ǻ' => 'A',
		'Ā' => 'A',
		'Ă' => 'A',
		'Ą' => 'A',
		'Ǎ' => 'A',
		'à' => 'a',
		'á' => 'a',
		'â' => 'a',
		'ã' => 'a',
		'å' => 'a',
		'ǻ' => 'a',
		'ā' => 'a',
		'ă' => 'a',
		'ą' => 'a',
		'ǎ' => 'a',
		'ª' => 'a',
		'Ç' => 'C',
		'Ć' => 'C',
		'Ĉ' => 'C',
		'Ċ' => 'C',
		'Č' => 'C',
		'ç' => 'c',
		'ć' => 'c',
		'ĉ' => 'c',
		'ċ' => 'c',
		'č' => 'c',
		'Ð' => 'D',
		'Ď' => 'D',
		'Đ' => 'D',
		'ð' => 'd',
		'ď' => 'd',
		'đ' => 'd',
		'È' => 'E',
		'É' => 'E',
		'Ê' => 'E',
		'Ë' => 'E',
		'Ē' => 'E',
		'Ĕ' => 'E',
		'Ė' => 'E',
		'Ę' => 'E',
		'Ě' => 'E',
		'è' => 'e',
		'é' => 'e',
		'ê' => 'e',
		'ë' => 'e',
		'ē' => 'e',
		'ĕ' => 'e',
		'ė' => 'e',
		'ę' => 'e',
		'ě' => 'e',
		'Ĝ' => 'G',
		'Ğ' => 'G',
		'Ġ' => 'G',
		'Ģ' => 'G',
		'Ґ' => 'G',
		'ĝ' => 'g',
		'ğ' => 'g',
		'ġ' => 'g',
		'ģ' => 'g',
		'ґ' => 'g',
		'Ĥ' => 'H',
		'Ħ' => 'H',
		'ĥ' => 'h',
		'ħ' => 'h',
		'І' => 'I',
		'Ì' => 'I',
		'Í' => 'I',
		'Î' => 'I',
		'Ї' => 'Yi',
		'Ï' => 'I',
		'Ĩ' => 'I',
		'Ī' => 'I',
		'Ĭ' => 'I',
		'Ǐ' => 'I',
		'Į' => 'I',
		'İ' => 'I',
		'і' => 'i',
		'ì' => 'i',
		'í' => 'i',
		'î' => 'i',
		'ï' => 'i',
		'ї' => 'yi',
		'ĩ' => 'i',
		'ī' => 'i',
		'ĭ' => 'i',
		'ǐ' => 'i',
		'į' => 'i',
		'ı' => 'i',
		'Ĵ' => 'J',
		'ĵ' => 'j',
		'Ķ' => 'K',
		'ķ' => 'k',
		'Ĺ' => 'L',
		'Ļ' => 'L',
		'Ľ' => 'L',
		'Ŀ' => 'L',
		'Ł' => 'L',
		'ĺ' => 'l',
		'ļ' => 'l',
		'ľ' => 'l',
		'ŀ' => 'l',
		'ł' => 'l',
		'Ñ' => 'N',
		'Ń' => 'N',
		'Ņ' => 'N',
		'Ň' => 'N',
		'ñ' => 'n',
		'ń' => 'n',
		'ņ' => 'n',
		'ň' => 'n',
		'ŉ' => 'n',
		'Ò' => 'O',
		'Ó' => 'O',
		'Ô' => 'O',
		'Õ' => 'O',
		'Ō' => 'O',
		'Ŏ' => 'O',
		'Ǒ' => 'O',
		'Ő' => 'O',
		'Ơ' => 'O',
		'Ø' => 'O',
		'Ǿ' => 'O',
		'ò' => 'o',
		'ó' => 'o',
		'ô' => 'o',
		'õ' => 'o',
		'ō' => 'o',
		'ŏ' => 'o',
		'ǒ' => 'o',
		'ő' => 'o',
		'ơ' => 'o',
		'ø' => 'o',
		'ǿ' => 'o',
		'º' => 'o',
		'Ŕ' => 'R',
		'Ŗ' => 'R',
		'Ř' => 'R',
		'ŕ' => 'r',
		'ŗ' => 'r',
		'ř' => 'r',
		'Ś' => 'S',
		'Ŝ' => 'S',
		'Ş' => 'S',
		'Ș' => 'S',
		'Š' => 'S',
		'ẞ' => 'SS',
		'ś' => 's',
		'ŝ' => 's',
		'ş' => 's',
		'ș' => 's',
		'š' => 's',
		'ſ' => 's',
		'Ţ' => 'T',
		'Ț' => 'T',
		'Ť' => 'T',
		'Ŧ' => 'T',
		'ţ' => 't',
		'ț' => 't',
		'ť' => 't',
		'ŧ' => 't',
		'Ù' => 'U',
		'Ú' => 'U',
		'Û' => 'U',
		'Ũ' => 'U',
		'Ū' => 'U',
		'Ŭ' => 'U',
		'Ů' => 'U',
		'Ű' => 'U',
		'Ų' => 'U',
		'Ư' => 'U',
		'Ǔ' => 'U',
		'Ǖ' => 'U',
		'Ǘ' => 'U',
		'Ǚ' => 'U',
		'Ǜ' => 'U',
		'ù' => 'u',
		'ú' => 'u',
		'û' => 'u',
		'ũ' => 'u',
		'ū' => 'u',
		'ŭ' => 'u',
		'ů' => 'u',
		'ű' => 'u',
		'ų' => 'u',
		'ư' => 'u',
		'ǔ' => 'u',
		'ǖ' => 'u',
		'ǘ' => 'u',
		'ǚ' => 'u',
		'ǜ' => 'u',
		'Ý' => 'Y',
		'Ÿ' => 'Y',
		'Ŷ' => 'Y',
		'ý' => 'y',
		'ÿ' => 'y',
		'ŷ' => 'y',
		'Ŵ' => 'W',
		'ŵ' => 'w',
		'Ź' => 'Z',
		'Ż' => 'Z',
		'Ž' => 'Z',
		'ź' => 'z',
		'ż' => 'z',
		'ž' => 'z',
		'Æ' => 'AE',
		'Ǽ' => 'AE',
		'ß' => 'ss',
		'Ĳ' => 'IJ',
		'ĳ' => 'ij',
		'Œ' => 'OE',
		'ƒ' => 'f',
		'Þ' => 'TH',
		'þ' => 'th',
		'Є' => 'Ye',
		'є' => 'ye',
	];

	/**
	 * Returns a string with all spaces converted to dashes (by default), accented
	 * characters converted to non-accented characters, and non word characters removed.
	 *
	 * @deprecated 3.2.7 Use Text::slug() instead.
	 * @param string $string the string you want to slug
	 * @param string $replacement will replace keys in map
	 * @return string
	 * @link https://book.cakephp.org/3.0/en/core-libraries/inflector.html#creating-url-safe-strings
	 */
	public static function slug($string, $replacement = '-') {
		$quotedReplacement = preg_quote($replacement, '/');

		$map = [
			'/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
			'/[\s\p{Zs}]+/mu' => $replacement,
			sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
		];

		$string = str_replace(
			array_keys(static::$_transliteration),
			static::$_transliteration,
			$string
		);

		return preg_replace(array_keys($map), array_values($map), $string);
	}

}
