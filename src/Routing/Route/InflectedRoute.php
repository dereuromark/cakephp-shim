<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Shim\Routing\Route;

use Cake\Routing\Route\Route;
use Cake\Utility\Inflector;

/**
 * This route class will transparently inflect the controller, action and plugin
 * routing parameters, so that requesting `/my_plugin/my_controller/my_action`
 * is parsed as `['plugin' => 'MyPlugin', 'controller' => 'MyController', 'action' => 'myAction']`
 *
 * Note that this is a shimmed version of 2.x - for upgraded projects (and to keep URL BC).
 * For new 3.x projects you should use the DashedRoute class instead.
 */
class InflectedRoute extends Route {

	/**
	 * Flag for tracking whether or not the defaults have been inflected.
	 *
	 * Default values need to be inflected so that they match the inflections that
	 * match() will create.
	 *
	 * @var bool
	 */
	protected $_inflectedDefaults = false;

	/**
	 * Parses a string URL into an array. If it matches, it will convert the
	 * controller and plugin keys to their CamelCased form and action key to
	 * camelBacked form.
	 *
	 * @param string $url The URL to parse
	 * @param string $method
	 * @return array|null An array of request parameters, or false on failure.
	 */
	public function parse(string $url, string $method = ''): ?array {
		$params = parent::parse($url, $method);
		if (!$params) {
			return null;
		}
		if (!empty($params['controller'])) {
			$params['controller'] = Inflector::camelize($params['controller']);
		}
		if (!empty($params['plugin'])) {
			$params['plugin'] = $this->_camelizePlugin($params['plugin']);
		}
		if (!empty($params['action'])) {
			$params['action'] = Inflector::variable(str_replace(
				'-',
				'_',
				$params['action']
			));
		}
		return $params;
	}

	/**
	 * Camelizes the previously underscored plugin route taking into account plugin vendors
	 *
	 * @param string $plugin Plugin name
	 * @return string
	 */
	protected function _camelizePlugin(string $plugin): string {
		if (strpos($plugin, '/') === false) {
			return Inflector::camelize($plugin);
		}
		list($vendor, $plugin) = explode('/', $plugin, 2);

		return Inflector::camelize($vendor) . '/' . Inflector::camelize($plugin);
	}

	/**
	 * Dasherizes the controller, action and plugin params before passing them on
	 * to the parent class.
	 *
	 * @param array $url Array of parameters to convert to a string.
	 * @param array $context An array of the current request context.
	 *   Contains information such as the current host, scheme, port, and base
	 *   directory.
	 * @return string|null Either false or a string URL.
	 */
	public function match(array $url, array $context = []): ?string {
		$url = $this->_underscore($url);
		if (!$this->_inflectedDefaults) {
			$this->_inflectedDefaults = true;
			$this->defaults = $this->_underscore($this->defaults);
		}
		return parent::match($url, $context);
	}

	/**
	 * Helper method for creating underscore keys in a URL array.
	 *
	 * @param array $url An array of URL keys.
	 * @return array
	 */
	protected function _underscore(array $url): array {
		foreach (['controller', 'plugin', 'action'] as $element) {
			if (!empty($url[$element])) {
				$url[$element] = Inflector::underscore($url[$element]);
			}
		}
		return $url;
	}

}
