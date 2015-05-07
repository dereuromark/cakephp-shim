<?php
namespace Shim\Controller\Component;

use Cake\Controller\Component as CoreComponent;
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

	public function initialize(array $config) {
		$this->Controller = $this->_registry->getController();
	}

}
