<?php

namespace Shim\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Utility\Hash;

/**
 * A behavior to assert data consistency regarding empty values.
 * It will check the schema and depending on nullable type or not transform empty string input
 * (from forms for example) into `null`.
 *
 * Careful with validation checking for isset(). In these cases it is better to use array_key_exists()
 * as this works also with (now) `null` values passed.
 */
class NullableBehavior extends Behavior {

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
		'on' => 'beforeMarshal', // beforeMarshal/afterSave
	];

	/**
	 * @param \Cake\Event\EventInterface $event
	 * @param \ArrayObject $data
	 * @param \ArrayObject $options
	 * @return void
	 */
	public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void {
		if ($this->_config['on'] !== 'beforeMarshal') {
			return;
		}

		$this->_processArray($data, $this->_table);
	}

	/**
	 * @param \Cake\Event\EventInterface $event
	 * @param \Cake\Datasource\EntityInterface $entity
	 * @param \ArrayObject $options
	 * @return void
	 */
	public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void {
		if ($this->_config['on'] !== 'beforeSave') {
			return;
		}

		$this->_processEntity($entity, $this->_table);
	}

	/**
	 * @param \ArrayObject|array $data
	 * @param \Cake\ORM\Table $table
	 * @return \ArrayObject
	 */
	protected function _processArray($data, Table $table) {
		$associations = [];
		/** @var \Cake\ORM\Association $association */
		foreach ($table->associations() as $association) {
			$associations[$association->getProperty()] = $association->getName();
		}

		foreach ($data as $key => $value) {
			if (array_key_exists($key, $associations)) {
				$data[$key] = $data[$key] === null ? null : $this->_processArray($data[$key], $table->getAssociation($associations[$key])->getTarget());
				continue;
			}
			$nullable = Hash::get((array)$table->getSchema()->getColumn($key), 'null');
			if ($nullable !== true) {
				continue;
			}
			if ($value !== '') {
				continue;
			}

			$default = Hash::get((array)$table->getSchema()->getColumn($key), 'default');
			$data[$key] = $default;
		}

		return $data;
	}

	/**
	 * @param \Cake\Datasource\EntityInterface $entity
	 * @param \Cake\ORM\Table $table
	 * @return \Cake\Datasource\EntityInterface
	 */
	protected function _processEntity(EntityInterface $entity, Table $table) {
		$associations = [];
		/** @var \Cake\ORM\Association $association */
		foreach ($table->associations() as $association) {
			$associations[$association->getProperty()] = $association->getName();
		}

		foreach ($entity->getDirty() as $field) {
			$value = $entity->get($field);

			if (array_key_exists($field, $associations)) {
				if ($value !== null) {
					if ($value instanceof EntityInterface) {
						$value = $this->_processEntity($value, $table->getAssociation($associations[$field])->getTarget());
					}
					if (is_array($value) || $value instanceof ArrayObject) {
						$value = $this->_processArray($value, $table->getAssociation($associations[$field])->getTarget());
					}

					$entity->set($field, $value);
				}

				continue;
			}

			$nullable = Hash::get((array)$table->getSchema()->getColumn($field), 'null');
			if ($nullable !== true) {
				continue;
			}
			if ($value !== '') {
				continue;
			}

			$default = Hash::get((array)$table->getSchema()->getColumn($field), 'default');
			$entity->set($field, $default);
		}

		return $entity;
	}

}
