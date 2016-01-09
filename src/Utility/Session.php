<?php
/**
 * Session class for CakePHP.
 *
 * CakePHP abstracts the handling of sessions.
 * There are several convenient methods to access session information.
 * This class is the implementation of those methods.
 * They are mostly used by the Session Component.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Model.Datasource
 * @since         CakePHP(tm) v .0.10.0.1222
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Shim\Utility;

use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Hash;

/**
 * Legacy BC wrapper for 2.x static access inside Model layer until it can be rewritten.
 * It only contains the read access and the session must have been started in the controller layer
 * prior to using this legacy access to the session. Otherwise it will fail.
 *
 * Note that you need to move away from this hack. It really is just for making upgrading easier
 * as you will sooner have a working app again. After that it must be removed.
 */
class Session {

	/**
	 * True if the Session is still valid
	 *
	 * @var bool
	 */
	public static $valid = false;

	/**
	 * Error messages for this session
	 *
	 * @var array
	 */
	public static $error = false;

	/**
	 * User agent string
	 *
	 * @var string
	 */
	protected static $_userAgent = '';

	/**
	 * Path to where the session is active.
	 *
	 * @var string
	 */
	public static $path = '/';

	/**
	 * Error number of last occurred error
	 *
	 * @var int
	 */
	public static $lastError = null;

	/**
	 * Start time for this session.
	 *
	 * @var int
	 */
	public static $time = false;

	/**
	 * Cookie lifetime
	 *
	 * @var int
	 */
	public static $cookieLifeTime;

	/**
	 * Time when this session becomes invalid.
	 *
	 * @var int
	 */
	public static $sessionTime = false;

	/**
	 * Current Session id
	 *
	 * @var string
	 */
	public static $id = null;

	/**
	 * Hostname
	 *
	 * @var string
	 */
	public static $host = null;

	/**
	 * Session timeout multiplier factor
	 *
	 * @var int
	 */
	public static $timeout = null;

	/**
	 * Number of requests that can occur during a session time without the session being renewed.
	 * This feature is only used when config value `Session.autoRegenerate` is set to true.
	 *
	 * @var int
	 * @see CakeSession::_checkValid()
	 */
	public static $requestCountdown = 10;

	/**
	 * Whether or not the init function in this class was already called
	 *
	 * @var bool
	 */
	protected static $_initialized = false;

	/**
	 * Session cookie name
	 *
	 * @var string
	 */
	protected static $_cookieName = null;


	/**
	 * Returns true if given variable is set in session.
	 *
	 * @param string $name Variable name to check for
	 * @return bool True if variable is there
	 */
	public static function check($name) {
		if (empty($name) || !static::_hasSession() || !static::start()) {
			return false;
		}

		return Hash::get($_SESSION, $name) !== null;
	}

	/**
	 * Starts the Session.
	 *
	 * @return bool True if session was started
	 */
	public static function start() {
		if (static::started()) {
			return true;
		}

		throw new InternalErrorException('You must start the session before using this class.');
	}

	/**
	 * Returns the session id.
	 * Calling this method will not auto start the session. You might have to manually
	 * assert a started session.
	 *
	 * Passing an id into it, you can also replace the session id if the session
	 * has not already been started.
	 * Note that depending on the session handler, not all characters are allowed
	 * within the session id. For example, the file session handler only allows
	 * characters in the range a-z A-Z 0-9 , (comma) and - (minus).
	 *
	 * @param string|null $id Id to replace the current session id
	 * @return string Session id
	 */
	public static function id($id = null) {
		if ($id) {
			static::$id = $id;
			session_id(static::$id);
		}
		if (static::started()) {
			return session_id();
		}
		return static::$id;
	}

	/**
	 * Returns true if session is valid.
	 *
	 * @return bool Success
	 */
	public static function valid() {
		if (static::start() && static::read('Config')) {
			if (static::_validAgentAndTime() && static::$error === false) {
				static::$valid = true;
			} else {
				throw new InternalErrorException('Session Highjacking Attempted !!!');
			}
		}
		return static::$valid;
	}

	/**
	 * Tests that the user agent is valid and that the session hasn't 'timed out'.
	 * Since timeouts are implemented in CakeSession it checks the current static::$time
	 * against the time the session is set to expire. The User agent is only checked
	 * if Session.checkAgent == true.
	 *
	 * @return bool
	 */
	protected static function _validAgentAndTime() {
		$config = static::read('Config');
		$validAgent = (
			Configure::read('Session.checkAgent') === false ||
			isset($config['userAgent']) && static::$_userAgent === $config['userAgent']
		);
		return ($validAgent && static::$time <= $config['time']);
	}

	/**
	 * Returns given session variable, or all of them, if no parameters given.
	 *
	 * @param string|null $name The name of the session variable (or a path as sent to Set.extract)
	 * @return mixed The value of the session variable, null if session not available,
	 *   session not started, or provided name not found in the session, false on failure.
	 */
	public static function read($name = null) {
		if (empty($name) && $name !== null) {
			return null;
		}
		if (!static::_hasSession()) {
			throw new InternalErrorException('You must start the session before using this class.');
		}
		if ($name === null) {
			return static::_returnSessionVars();
		}
		$result = Hash::get($_SESSION, $name);

		if (isset($result)) {
			return $result;
		}
		return null;
	}

	/**
	 * Returns all session variables.
	 *
	 * @return mixed Full $_SESSION array, or false on error.
	 */
	protected static function _returnSessionVars() {
		if (!empty($_SESSION)) {
			return $_SESSION;
		}
		throw new InternalErrorException('You must start the session before using this class.');
	}

	/**
	 * Returns whether a session exists
	 *
	 * @return bool
	 */
	protected static function _hasSession() {
		return static::started() || isset($_COOKIE[session_name()]) || (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg');
	}

	/**
	 * Determine if Session has been started.
	 *
	 * @return bool True if session has been started.
	 */

	public static function started() {
		if (function_exists('session_status')) {
			return isset($_SESSION) && (session_status() === PHP_SESSION_ACTIVE);
		}
		return isset($_SESSION) && session_id();
	}

}
