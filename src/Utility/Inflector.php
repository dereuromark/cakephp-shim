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
class Inflector extends CoreInflector
{

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
    public static function slug($string, $replacement = '-')
    {
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
