<?php

namespace Shim\Controller;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Datasource\Paging\Exception\PageOutOfBoundsException;
use Cake\Datasource\Paging\NumericPaginator;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Datasource\QueryInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\RedirectException;
use Cake\Routing\Router;

/**
 * @mixin \Cake\Controller\Controller
 */
trait RedirectOutOfBoundsTrait {

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
		if (!is_object($object)) {
			$object = $this->fetchTable($object);
		}

		$defaults = (array)Configure::read('Paginator');
		$settings += $this->paginate + $defaults;

		/** @var class-string<\Cake\Datasource\Paging\PaginatorInterface> $paginator */
		$paginator = App::className(
			$settings['className'] ?? NumericPaginator::class,
			'Datasource/Paging',
			'Paginator',
		);
		$paginator = new $paginator();
		unset($settings['className']);

		try {
			$results = $paginator->paginate(
				$object,
				$this->request->getQueryParams(),
				$settings,
			);
		} catch (PageOutOfBoundsException $exception) {
			$params = $exception->getAttributes()['pagingParams'] ?? [];
			$currentPage = $params['requestedPage'] > 1 ? $params['requestedPage'] : null;
			$lastPage = $params['pageCount'] > 1 ? $params['pageCount'] : null;
			if ($lastPage !== $currentPage && !$this->request->getParam('_ext')) {
				$url = Router::url(['?' => ['page' => $lastPage] + $this->request->getQuery()]);

				throw new RedirectException($url);
			}

			throw new NotFoundException(null, null, $exception);
		}

		return $results;
	}

}
