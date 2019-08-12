<?php
namespace Shim\Controller\Component;

use Cake\Controller\Component as CoreComponent;
use Cake\Core\Configure;
use Shim\Deprecations;

/**
 * Convenience class that automatically provides the controller
 * instance via `$this->Controller`.
 */
class Component extends CoreComponent {

	/**
	 * @var \Cake\Controller\Controller
	 */
	public $Controller;

	/**
	 * @param array $config
	 * @return void
	 */
	public function initialize(array $config) {
		$this->Controller = $this->_registry->getController();

		if (Deprecations::enabled('actionNames') || Configure::read('Shim.assertActionNames')) {
			$this->_assertValidActionNames();
		}
	}

	/**
	 * @return void
	 */
	protected function _assertValidActionNames() {
		/** @var string|false $parentClass */
		$parentClass = get_parent_class($this->Controller);
		if (!$parentClass) {
			return;
		}
		$parentClassMethods = get_class_methods($parentClass);
		$subClassMethods = get_class_methods($this->Controller);
		$classMethods = array_diff($subClassMethods, $parentClassMethods);

		foreach ($classMethods as $classMethod) {
			$this->_assertActioName($classMethod);
		}
	}

	/**
	 * @param string $classMethod
	 * @return void
	 */
	protected function _assertActioName($classMethod) {
		if (substr($classMethod, 0, 1) !== '_' && strpos($classMethod, '_') !== false) {
			Deprecations::error('Invalid controller action name ' . $classMethod . ', no underscore expected, should be camelBacked.');
		}
	}

}
