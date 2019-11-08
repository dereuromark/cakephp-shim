<?php

namespace Shim\Utility;

use Cake\Utility\Inflector as CoreInflector;

/**
 * Backport 4.x inflector behavior to 3.x:
 * - pluralize(): index => indexes
 *
 * This class will be removed in 4.x.
 */
class Inflector extends CoreInflector {

	/**
	 * Returns $word in plural form.
	 *
	 * @param string $word Word in singular
	 * @return string Word in plural
	 */
	public static function pluralize($word) {
		static::rules('plural', ['/(index)$/i' => '\1es']);

		return parent::pluralize($word);
	}

}
