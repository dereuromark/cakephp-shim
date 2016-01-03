<?php
namespace Shim\Controller\Component;

use Cake\Controller\Component as CoreComponent;
use Cake\Core\Configure;
use Cake\Event\Event;

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

		if (Configure::read('Shim.assertActionNames')) {
			$this->_assertValidActionNames();
		}
	}

	/**
	 * @return void
	 */
	protected function _assertValidActionNames() {
		$parentClassMethods = get_class_methods(get_parent_class($this->Controller));
		$subClassMethods = get_class_methods($this->Controller);
		$classMethods = array_diff($subClassMethods, $parentClassMethods);

		foreach ($classMethods as $classMethod) {
			if (substr($classMethod, 0, 1) !== '_' && strpos($classMethod, '_' !== false)) {
				trigger_error('Invalid controller action name ' . $classMethod . ', no underscore expected, should be camelBacked.', E_USER_DEPRECATED);
			}
		}
	}
}
