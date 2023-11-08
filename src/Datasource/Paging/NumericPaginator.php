<?php

namespace Shim\Datasource\Paging;

use Cake\Datasource\Paging\NumericPaginator as CoreNumericPaginator;
use Cake\Datasource\QueryInterface;
use Cake\Datasource\RepositoryInterface;

class NumericPaginator extends CoreNumericPaginator {

	/**
	 * Get the settings for a $model. If there are no settings for a specific
	 * repository, the general settings will be used.
	 *
	 * @param string $alias Model name to get settings for.
	 * @param array<string, mixed> $settings The settings which is used for combining.
	 * @return array<string, mixed> An array of pagination settings for a model,
	 *   or the general settings.
	 */
	protected function getDefaults(string $alias, array $settings): array {
		$this->_defaultConfig['contain'] = null;
		$this->_defaultConfig['conditions'] = null;
		$this->_defaultConfig['fields'] = null;

		return parent::getDefaults($alias, $settings);
	}

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
		unset($data['defaults']['contain']);

		$fields = null;
		if (!empty($data['options']['fields'])) {
			$fields = $data['options']['fields'];
		}

		unset($data['options']['fields']);
		unset($data['defaults']['fields']);

		$conditions = null;
		if (!empty($data['options']['conditions'])) {
			$conditions = $data['options']['conditions'];
		}

		unset($data['options']['conditions']);
		unset($data['defaults']['conditions']);

		$query = parent::getQuery($object, $query, $data);
		if ($contain) {
			/** @var \Cake\ORM\Query\SelectQuery $query */
			$query->contain($contain);
		}

		if ($fields) {
			/** @var \Cake\ORM\Query\SelectQuery $query */
			$query->select($fields);
		}

		if ($conditions) {
			/** @var \Cake\ORM\Query\SelectQuery $query */
			$query->where($conditions);
		}

		return $query;
	}

}
