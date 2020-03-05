<?php

namespace Shim\View\Helper;

use Cake\View\Helper;

/**
 * Cookie Helper.
 */
class CookieHelper extends Helper {

	/**
	 * Return all cookie names available.
	 *
	 * @return array
	 */
	public function getCookies() {
		$cookies = $this->_View->getRequest()->getCookieParams();
		if (!$cookies) {
			return [];
		}

		return array_keys($cookies);
	}

	/**
	 * Reads a cookie value for a key or return values for all keys.
	 *
	 * In your view: `$this->Cookie->read('key');`
	 *
	 * @param string|null $key The name of the cookie key you want to read
	 * @param string|null $default
	 * @return mixed Values from the cookie vars
	 */
	public function read($key = null, $default = null) {
		return $this->_View->getRequest()->getCookie($key, $default);
	}

	/**
	 * Checks if a cookie key has been set.
	 *
	 * In your view: `$this->Cookie->check('key');`
	 *
	 * @param string $key Cookie name to check.
	 * @return bool
	 */
	public function check($key) {
		return $this->_View->getRequest()->getCookie($key) !== null;
	}

	/**
	 * Event listeners.
	 *
	 * @return array
	 */
	public function implementedEvents(): array {
		return [];
	}

}
