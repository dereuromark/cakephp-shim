<?php
App::uses('Controller', 'Controller');
App::uses('ShimException', 'Shim.Error');

/**
 * DRY Controller stuff
 */
class ShimController extends Controller {

	/**
	 * @var array
	 * @link https://github.com/cakephp/cakephp/pull/857
	 */
	public $paginate = [];

	/**
	 * Calling initialize() here because in 3.0 it's called right before that
	 * method but we don't want to override the whole constructor just because
	 * of that.
	 *
	 * @return void
	 */
	protected function _mergeControllerVars() {
		$this->initialize();
		parent::_mergeControllerVars();
	}

	/**
	 * Replaces the 2.x constructClasses() method to inject custom initialize() code,
	 * as that one will be gone.
	 *
	 * @return void
	 */
	public function initialize() {
	}

	/**
	 * Load a component dynamically.
	 *
	 * @param string $component Component name
	 * @param array $options
	 * @return Component A component object, Either the existing loaded component or a new one.
	 */
	public function loadComponent($component, array $options = []) {
		return $this->Components->load($component, $options);
	}

	/**
	 * Returns the ComponentsCollection
	 *
	 * @return ComponentCollection
	 */
	public function components() {
		return $this->Components;
	}

	/**
	 * Add headers for IE8 etc to fix caching issues in those stupid browsers
	 *
	 * @override to fix IE cacheing issues
	 * @return void
	 */
	public function disableCache() {
		$this->response->header([
			'Pragma' => 'no-cache',
		]);
		parent::disableCache();
	}

	/**
	 * Handles automatic pagination of model records.
	 *
	 * @override to support defaults like limit, querystring settings
	 * @param Model|string|null $object Model to paginate (e.g: model instance, or 'Model', or 'Model.InnerModel')
	 * @param string|array $scope Conditions to use while paginating
	 * @param array $whitelist List of allowed options for paging
	 * @return array Model query results
	 */
	public function paginate($object = null, $scope = [], $whitelist = []) {
		$defaultSettings = (array)Configure::read('Paginator');
		if (Configure::read('Shim.disableRecursive')) {
			$defaultSettings += ['contain' => []];
		} else {
			$defaultSettings += ['contain' => null];
		}

		$this->paginate += $defaultSettings;

		return parent::paginate($object, $scope, $whitelist);
	}

	/**
	 * Hook to monitor headers being sent.
	 *
	 * @return void
	 */
	public function afterFilter() {
		parent::afterFilter();

		if (Configure::read('Shim.monitorHeaders') && $this->name !== 'CakeError') {
			if (headers_sent($filename, $linenum)) {
				$message = sprintf('Headers already sent in %s on line %s', $filename, $linenum);
				if (Configure::read('debug') && Configure::read('Shim.monitorHeaders') === 'exception') {
					throw new ShimException($message);
				}
				trigger_error($message, E_USER_NOTICE);
			}
		}
	}

}
