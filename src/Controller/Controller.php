<?php

namespace Shim\Controller;

use Cake\Controller\Controller as CoreController;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Exception;

/**
 * DRY Controller stuff
 */
class Controller extends CoreController {

	/**
	 * Shim components and helpers as class property array.
	 *
	 * @return void
	 */
	public function initialize(): void {
		if (!empty($this->components)) {
			foreach ($this->components as $component => $config) {
				if (!is_string($component)) {
					$component = $config;
					$config = [];
				}
				$this->loadComponent($component, $config);
			}
			$this->components = null;
		}

		if (!empty($this->helpers)) {
			$this->viewBuilder()->setHelpers($this->helpers);

			$this->helpers = null;
		}

		parent::initialize();
	}

	/**
	 * Add headers for IE8 etc to fix caching issues in those stupid browsers
	 *
	 * @return void
	 */
	public function disableCache() {
		$this->response = $this->response
			->withHeader('Pragma', 'no-cache')
			->withDisabledCache();
	}

	/**
	 * Hook to monitor headers being sent.
	 *
	 * This, if desired, adds a check if your controller actions are cleanly built and no headers
	 * or output is being sent prior to the response class, which should be the only one doing this.
	 *
	 * @param \Cake\Event\EventInterface $event An Event instance
	 * @throws \Exception
	 * @return \Cake\Http\Response|null
	 */
	public function afterFilter(EventInterface $event) {
		if (Configure::read('Shim.monitorHeaders') && $this->name !== 'Error' && PHP_SAPI !== 'cli') {
			if (headers_sent($filename, $lineNumber)) {
				$message = sprintf('Headers already sent in %s on line %s', $filename, $lineNumber);
				if (Configure::read('debug')) {
					throw new Exception($message);
				}
				trigger_error($message);
			}
		}

		return null;
	}

}
