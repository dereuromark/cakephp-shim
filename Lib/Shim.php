<?php
App::uses('ShimException', 'Shim.Error');
/**
 * Shim class.
 */
class Shim {

	/**
	 * The name of the shim that checks that model associations are defined in
	 * Model::initialize() but not in association properties Model::$hasOne,
	 * Model::$belongsTo etc.
	 *
	 * @var string
	 */
	const RELATIONSHIP_PROPERTIES = 'Shim.warnAboutRelationProperty';

	/**
	 * The name of the shim that checks that model validation rules are defined
	 * in Model::validationDefault() but not in Model::$validate.
	 *
	 * @var string
	 */
	const VALIDATE_PROPERTY = 'Shim.warnAboutValidateProperty';

	/**
	 * Checks whether the shim is enabled and generates an error or an exception.
	 *
	 * @param string $name    Name of the shim.
	 * @param string $message Error/exception message.
	 * @throws ShimException
	 */
	public static function check($name, $message) {
		if ($warn = Configure::read($name)) {
			if (Configure::read('debug') && $warn === 'exception') {
				throw new ShimException($message, 500);
			}
			trigger_error($message, E_USER_DEPRECATED);
		}
	}
}
