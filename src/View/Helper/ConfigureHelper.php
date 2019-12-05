<?php

namespace Shim\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Configure Helper.
 *
 * Configure reading from the view. BC file to avoid static calls in template code.
 */
class ConfigureHelper extends Helper {

	/**
	 * Reads a Configure value for a key or returns values for all keys.
	 *
	 * In your view: `$this->Configure->read('Key.subKey');`
	 * Calling the method without a param will return all vars
	 *
	 * @param string|null $name The name of the Configure key you want to read
	 * @return mixed Values
	 */
	public function read($name = null) {
		return Configure::read($name);
	}

	/**
	 * Checks if a Configure key has been set.
	 *
	 * In your view: `$this->Configure->check('Controller.sessKey');`
	 *
	 * @param string $name Configure key to check.
	 * @return bool
	 */
	public function check($name) {
		return Configure::check($name);
	}

	/**
	 * Reads and deletes a variable from Configure.
	 *
	 * @param string $name The key to read and remove (or a path as sent to Hash.extract).
	 * @return mixed The value of the Configure variable, null if not available
	 */
	public function consume($name) {
		return Configure::consume($name);
	}

	/**
	 * Used to get information stored in Configure. It's not
	 * possible to store `null` values in Configure.
	 *
	 * Acts as a wrapper around Configure::read() and Configure::check().
	 * The configure key/value pair fetched via this method is expected to exist.
	 * In case it does not an exception will be thrown.
	 *
	 * Usage:
	 * ```
	 * Configure::readOrFail('Name'); will return all values for Name
	 * Configure::readOrFail('Name.key'); will return only the value of Configure::Name[key]
	 * ```
	 *
	 * @param string $name Variable to obtain. Use '.' to access array elements.
	 * @return mixed Value stored in configure.
	 */
	public function readOrFail($name) {
		return Configure::readOrFail($name);
	}

	/**
	 * @return string Current version of CakePHP
	 */
	public function version() {
		return Configure::version();
	}

}
