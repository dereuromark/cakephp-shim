<?php

App::uses('Component', 'Controller');
App::uses('ShimException', 'Shim.Error');

/**
 * A component included in every app to take care of common stuff.
 *
 * @author Mark Scherer
 * @copyright 2012 Mark Scherer
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class ShimComponent extends Component {

	/**
	 * Trigger a warning about named param leftovers.
	 * But don't care about search engine hits or alike, that still
	 * have the old URL.
	 * Also allows auto-301-redirecting to the new ressource after upgrading the code.
	 *
	 * @param \Controller|null $Controller
	 * @return \Cake\Network\Response|null
	 * @throws \Exception
	 */
	public function startup(Controller $Controller = null) {
		$this->Controller = $Controller;

		$this->_checkPaths();

		if ($Controller->name === 'CakeError' || empty($Controller->request->params['named'])) {
			return null;
		}

		// Deprecation notices, but only for internally triggered ones
		if (Configure::read('Shim.warnAboutNamedParams') && ($referer = $Controller->request->referer(true)) && $referer !== '/') {
			$message = 'Named params ' . json_encode($Controller->request->params['named']) . ' - from ' . $referer;
			if (Configure::read('debug') && Configure::read('Shim.warnAboutNamedParams') === 'exception') {
				throw new ShimException($message);
			}
			trigger_error($message, E_USER_DEPRECATED);
		}

		if (Configure::read('Shim.handleNamedParams')) {
			if (Configure::read('Shim.handleNamedParams') === 'exception') {
				throw new NotFoundException();
			}
			// By default true to 301 redirect to the correct URL
			return $this->_redirectNamedParams();
		}
	}

	/**
	 * Checks if all paths are correctly set up, only checked in debug mode for performance.
	 *
	 * Paths must have
	 * - trailing DS
	 * - DS as constant, not hardcoded /
	 *
	 * @return void
	 * @throws ShimException
	 */
	protected function _checkPaths() {
		if (!Configure::read('debug')) {
			return;
		}
		if (!Configure::read('Shim.checkPaths')) {
			return;
		}

		$pathArray = App::paths();
		foreach ($pathArray as $type => $paths) {
			foreach ($paths as $path) {
				if (substr($path, -1, 1) !== DS) {
					throw new Exception('All paths need to have a trailing DS.');
				}
				// We need to exclude some buggy case on WIN where the path would be `...\vendors/cakephp/...`, including hardcoded /
				// Probably coming from composer or plugin installer.
				if ($type === 'Vendor' || $type === 'Plugin') {
					$path = str_replace('\vendors/cakephp/', DS . 'vendors' . DS . 'cakephp' . DS, $path);
				}

				if (str_replace(['/', '\\'], DS, $path) !== $path) {
					throw new ShimException('All paths need to have a DS as separator, not a hardcoded / or \ slash.');
				}
			}
		}
	}

	/**
	 * @return \Cake\Network\Response
	 */
	protected function _redirectNamedParams() {
		$url = $this->_buildUrl();

		// Flash message if debug mode is on and the Flash component (Tools plugin or 2.7 core?) is available.
		if (Configure::read('debug') && isset($this->Controller->Flash)) {
			$to = Router::url($url);
			$from = $this->Controller->request->query->url;
			$this->Controller->Flash->warning(sprintf('Auto 301 redirect from %s to %s', $from, $to));
		}

		// We only use 301 in productive mode as the browser "remembers" them and makes debugging painful in local dev.
		$status = Configure::read('debug') ? 302 : 301;
		return $this->Controller->redirect($url, $status);
	}

	/**
	 * Build query string URL from named params.
	 *
	 * @return array
	 */
	protected function _buildUrl() {
		$params = $this->Controller->request->params;

		$url = ['?' => $this->Controller->request->query];
		// Named params have a lower prio
		$url['?'] += $params['named'];
		$url['controller'] = $params['controller'];
		$url['action'] = $params['action'];
		$url['plugin'] = $params['plugin'];
		foreach ((array)Configure::read('Routing.prefixes') as $prefix) {
			if (isset($params['prefix'])) {
				$url['prefix'] = $params['prefix'];
			}
		}
		// The passed parms also need to be transferred
		$url += $params['pass'];
		return $url;
	}

}
