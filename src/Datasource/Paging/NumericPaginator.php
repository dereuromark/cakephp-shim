<?php

namespace Shim\Datasource\Paging;

use Cake\Datasource\Paging\NumericPaginator as CoreNumericPaginator;
use Cake\Datasource\QueryInterface;
use Cake\Datasource\RepositoryInterface;

class NumericPaginator extends CoreNumericPaginator {

	/**
	 * Get query for fetching paginated results.
	 *
	 * @param \Cake\Datasource\RepositoryInterface $object Repository instance.
	 * @param \Cake\Datasource\QueryInterface|null $query Query Instance.
	 * @param array<string, mixed> $data Pagination data.
	 * @return \Cake\Datasource\QueryInterface
	 */
	protected function getQuery(RepositoryInterface $object, ?QueryInterface $query, array $data): QueryInterface {
		$contain = null;
		if (!empty($data['options']['contain'])) {
			$contain = $data['options']['contain'];
		}

		unset($data['options']['contain']);

		$query = parent::getQuery($object, $query, $data);
		if ($contain) {
			$query->contain($contain);
		}

		return $query;
	}

}
