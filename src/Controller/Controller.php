<?php

namespace Shim\Controller;

use Cake\Controller\Controller as CoreController;
use Cake\Core\Configure;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Datasource\QueryInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\Event\EventInterface;
use Exception;

/**
 * DRY Controller stuff
 *
 * Note: Make sure to use attribute #[\AllowDynamicProperties] on your classes
 * that use this to avoid this blowing up in PHP 8.2+.
 *
 * @property array|null $components
 * @property array|null $helpers
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
			$this->components = [];
		}

		if (!empty($this->helpers)) {
			$this->viewBuilder()->setHelpers($this->helpers);

			$this->helpers = [];
		}

		parent::initialize();
	}

	/**
	 * Add headers for IE8 etc to fix caching issues in those stupid browsers
	 *
	 * @return void
	 */
	public function disableCache(): void {
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
	 * @return void
	 */
	public function afterFilter(EventInterface $event): void {
		if (Configure::read('Shim.monitorHeaders') && $this->name !== 'Error' && PHP_SAPI !== 'cli') {
			if (headers_sent($filename, $lineNumber)) {
				$message = sprintf('Headers already sent in %s on line %s', $filename, $lineNumber);
				if (Configure::read('debug')) {
					throw new Exception($message);
				}
				trigger_error($message);
			}
		}
	}

	/**
	 * Handles pagination of records in Table objects.
	 *
	 * Will load the referenced Table object, and have the paginator
	 * paginate the query using the request date and settings defined in `$this->paginate`.
	 *
	 * This method will also make the PaginatorHelper available in the view.
	 *
	 * @link https://book.cakephp.org/5/en/controllers.html#paginating-a-model
	 * @param \Cake\Datasource\RepositoryInterface|\Cake\Datasource\QueryInterface|string|null $object Table to paginate
	 * (e.g: Table instance, 'TableName' or a Query object)
	 * @param array<string, mixed> $settings The settings/configuration used for pagination. See {@link \Cake\Controller\Controller::$paginate}.
	 * @throws \Cake\Http\Exception\NotFoundException When a page out of bounds is requested.
	 * @return \Cake\Datasource\Paging\PaginatedInterface
	 */
	public function paginate(
		RepositoryInterface|QueryInterface|string|null $object = null,
		array $settings = [],
	): PaginatedInterface {
		$defaults = (array)Configure::read('Paginator');
		$settings += $this->paginate + $defaults;

		return parent::paginate($object, $settings);
	}

}
