<?php
App::uses('ShimException', 'Shim.Error');
/**
 * Shim class.
 */
class Shim {

	/**
	 * The name of the shim that checks the usage of the deprecated Model::bindModel() method.
	 */
	const BIND_MODEL_METHOD = 'Shim.warnAboutBindModelMethod';

	/**
	 * The name of the shim that checks the paths.
	 */
	const CHECK_PATHS = 'Shim.checkPaths';

	/**
	 * The name of the shim that checks the usage of the deprecated Model::field() method.
	 */
	const DEPRECATE_FIELD = 'Shim.deprecateField';

	/**
	 * The name of the shim that checks the usage of the deprecated Model::hasAny() method.
	 */
	const DEPRECATE_HAS_ANY = 'Shim.deprecateHasAny';

	/**
	 * The name of the shim that checks the usage of the deprecated Model::saveField() method.
	 */
	const DEPRECATE_SAVE_FIELD = 'Shim.deprecateSaveField';

	/**
	 * The name of the shim that checks the usage of the deprecated parameters in Model::save() method.
	 */
	const DEPRECATE_SAVE_PARAMS = 'Shim.deprecateSaveParams';

	/**
	 * The name of the shim that checks the usage of recursive in pagination.
	 */
	const DISABLE_RECURSIVE = 'Shim.disableRecursive';

	/**
	 * The name of the shim that handles named parameters in URLs.
	 */
	const HANDLE_NAMED_PARAMS = 'Shim.handleNamedParams';

	/**
	 * The name of the shim that checks the validity of URLs.
	 */
	const HANDLE_SEO = 'Shim.handleSeo';

	/**
	 * The name of the shim that checks the usage of JSON options.
	 */
	const JSON_OPTIONS = 'Shim.jsonOptions';

	/**
	 * The name of the shim that checks the usage of ContainableBehavior.
	 */
	const MISSING_CONTAIN = 'Shim.warnAboutMissingContain';

	/**
	 * The name of the shim that checks the usage of id parameter in Model::delete() method.
	 */
	const MODEL_DELETE = 'Shim.modelDelete';

	/**
	 * The name of the shim that checks the usage of id parameter in Model::exists() method.
	 */
	const MODEL_EXISTS = 'Shim.modelExists';

	/**
	 * The name of the shim that monitors the headers being sent.
	 */
	const MONITOR_HEADERS = 'Shim.monitorHeaders';

	/**
	 * The name of the shim that checks named parameters in URLs.
	 */
	const NAMED_PARAMS = 'Shim.warnAboutNamedParams';

	/**
	 * The name of the shim that checks the routing parameters in URLs.
	 */
	const OLD_ROUTING = 'Shim.warnAboutOldRouting';

	/**
	 * The name of the shim that checks that model associations are defined in
	 * Model::initialize() but not in association properties Model::$hasOne,
	 * Model::$belongsTo etc.
	 */
	const RELATIONSHIP_PROPERTIES = 'Shim.warnAboutRelationProperty';

	/**
	 * The name of the shim that checks that model validation rules are defined
	 * in Model::validationDefault() but not in Model::$validate.
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
