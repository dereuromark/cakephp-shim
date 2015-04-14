<?php
namespace Shim\View\Helper;

use Cake\View\Helper;

/**
 * Session Helper.
 *
 * Session reading from the view. BC file.
 */
class SessionHelper extends Helper {

	/**
	 * Reads a session value for a key or returns values for all keys.
	 *
	 * In your view: `$this->Session->read('Controller.sessKey');`
	 * Calling the method without a param will return all session vars
	 *
	 * @param string|null $name The name of the session key you want to read
	 * @return mixed Values from the session vars
	 */
	public function read($name = null) {
		return $this->request->session()->read($name);
	}

	/**
	 * Checks if a session key has been set.
	 *
	 * In your view: `$this->Session->check('Controller.sessKey');`
	 *
	 * @param string $name Session key to check.
	 * @return bool
	 */
	public function check($name) {
		return $this->request->session()->check($name);
	}

	/**
	 * Reads and deletes a variable from session.
	 *
	 * @param string $name The key to read and remove (or a path as sent to Hash.extract).
	 * @return mixed The value of the session variable, null if session not available,
	 *   session not started, or provided name not found in the session.
	 */
	public function consume($name) {
		return $this->request->session()->consume($name);
	}

	/**
	 * Event listeners.
	 *
	 * @return array
	 */
	public function implementedEvents() {
		return [];
	}
}
