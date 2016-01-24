<?php

namespace Shim\Model\Table;

use Cake\ORM\Table as CoreTable;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Event\Event;
use Cake\Datasource\Exception\RecordNotFoundException;

class Table extends CoreTable {

	public $order = null;

	public $createdField = 'created';

	public $modifiedField = 'modified';

	public $validationDomain = 'default';

	/**
	 * initialize()
	 *
	 * All models will automatically get Timestamp behavior attached
	 * if created or modified exists.
	 *
	 * @param array $config
	 * @return void
	 */
	public function initialize(array $config) {
		// Shims
		if (isset($this->useTable)) {
			$this->table($this->useTable);
		}
		if (isset($this->primaryKey)) {
			$this->primaryKey($this->primaryKey);
		}
		if (isset($this->displayField)) {
			$this->displayField($this->displayField);
		}
		$this->_shimRelations();

		$this->_prefixOrderProperty();

		if (isset($this->actsAs)) {
			foreach ($this->actsAs as $name => $options) {
				if (is_numeric($name)) {
					$name = $options;
					$options = [];
				}
				$this->addBehavior($name, $options);
			}
		}

		if (
			$this->createdField && $this->hasField($this->createdField)
			|| $this->modifiedField && $this->hasField($this->modifiedField)
		) {
			$this->addBehavior('Timestamp');
		}
	}

	/**
	 * Shim the 2.x way of class properties for relations.
	 *
	 * @return void
	 */
	protected function _shimRelations() {
		if (!empty($this->belongsTo)) {
			foreach ($this->belongsTo as $k => $v) {
				if (is_int($k)) {
					$k = $v;
					$v = [];
				}
				$v = $this->_parseRelation($v);
				$this->belongsTo(Inflector::pluralize($k), $v);
			}
		}
		if (!empty($this->hasOne)) {
			foreach ($this->hasOne as $k => $v) {
				if (is_int($k)) {
					$k = $v;
					$v = [];
				}
				$v = $this->_parseRelation($v);
				$this->hasOne(Inflector::pluralize($k), $v);
			}
		}
		if (!empty($this->hasMany)) {
			foreach ($this->hasMany as $k => $v) {
				if (is_int($k)) {
					$k = $v;
					$v = [];
				}
				$v = $this->_parseRelation($v);
				$this->hasMany(Inflector::pluralize($k), $v);
			}
		}
		if (!empty($this->hasAndBelongsToMany)) {
			foreach ($this->hasAndBelongsToMany as $k => $v) {
				if (is_int($k)) {
					$k = $v;
					$v = [];
				}
				$v = $this->_parseRelation($v);
				$this->belongsToMany(Inflector::pluralize($k), $v);
			}
		}
	}

	/**
	 * @param array $array
	 * @return array
	 * @throws \Exception
	 */
	protected function _parseRelation($array) {
		if (isset($array['unique'])) {
			if ($array['unique'] === 'keepExisting') {
				throw new \Exception('A HABTM relation "unique" config must be transformed into a valid "saveStrategy" one.');
			}
			$array['saveStrategy'] = $array['unique'] ? 'replace' : 'append';
		}

		if (!empty($array['className'])) {
			$array['className'] = Inflector::pluralize($array['className']);
		}
		if (!empty($array['associationForeignKey'])) {
			$array['targetForeignKey'] = $array['associationForeignKey'];
		}

		if (!empty($array['conditions']) && is_array($array['conditions'])) {
			$conditions = [];
			foreach ($array['conditions'] as $k => $v) {
				$conditions[$this->_pluralizeModelName($k)] = $v;
			}
			$array['conditions'] = $conditions;
		}

		$array = array_filter($array);
		return $array;
	}

	/*
	 * @param string $key
	 * @return string
	 */
	protected function _pluralizeModelName($key) {
		$pos = strpos($key, '.');
		if ($pos !== false) {
			$key = Inflector::pluralize(substr($key, 0, $pos)) . '.' . substr($key, $pos + 1);
		}
		return $key;
	}

	/**
	 * Shim the 2.x way of validate class properties.
	 *
	 * @param Validator $validator
	 * @return Validator
	 */
	public function validationDefault(Validator $validator) {
		if (!empty($this->validate)) {
			foreach ($this->validate as $field => $rules) {
				if (is_int($field)) {
					$field = $rules;
					$rules = [];
				}
				if (!$rules) {
					continue;
				}

				foreach ((array)$rules as $key => $rule) {
					if (isset($rule['required'])) {
						$validator->requirePresence($field, $rule['required']);
						unset($rules[$key]['required']);
					}
					if (isset($rule['allowEmpty'])) {
						$validator->allowEmpty($field, $rule['allowEmpty']);
						unset($rules[$key]['allowEmpty']);
					}
					if (isset($rule['message'])) {
						if (is_array($rule['message'])) {
							$name = array_shift($rule['message']);
							//$args = array_slice($rule['message'], 1);
							$args = $this->_translateArgs($rule['message']);
							$message = __d($this->validationDomain, $name, $args);
						} else {
							$message = __d($this->validationDomain, $rule['message']);
						}
						$rules[$key]['message'] = $message;
					}

					if (is_string($rule)) {
						$rules[$key] = ['rule' => $rule];
					}

					if (!empty($rules[$key]['rule']) && ($rules[$key]['rule'] === 'notEmpty' || $rules[$key]['rule'] === ['notEmpty'])) {
						$rules[$key]['rule'] =  'notBlank';
					}

					if (!empty($rules[$key]['rule']) && ($rules[$key]['rule'] === 'isUnique' || $rules[$key]['rule'] === ['isUnique'])) {
						$rules[$key]['rule'] =  'validateUnique';
						$rules[$key]['provider'] = 'table';
					}
				}

				$validator->add($field, $rules);
			}
		}

		return $validator;
	}

	/**
	 * Applies translations to validator arguments.
	 *
	 * @param array $args The args to translate
	 * @return array Translated args.
	 */
	protected function _translateArgs($args) {
		foreach ((array)$args as $k => $arg) {
			if (is_string($arg)) {
				$args[$k] = __d($this->validationDomain, $arg);
			}
		}
		return $args;
	}

	/**
	 * Shim to provide 2.x way of find('first') for easier upgrade.
	 *
	 * @param string $type
	 * @param array $options
	 * @return \Cake\ORM\Query
	 */
	public function find($type = 'all', $options = []) {
		if ($type === 'first') {
			return parent::find('all', $options)->first();
		}
		if ($type === 'count') {
			return parent::find('all', $options)->count();
		}
		return parent::find($type, $options);
	}

	/*
	 * Overwrite findList() to make it work as in 2.x when only providing
	 * 1-2 fields to select (no keyField/valueField).
	 *
	 * @param \Cake\ORM\Query $query The query to find with
	 * @param array $options The options for the find
	 * @return \Cake\ORM\Query The query builder
	 */
	public function findList(Query $query, array $options) {
		if (!isset($options['keyField']) && !isset($options['valueField'])) {
			$select = $query->clause('select');
			if ($select && count($select) <= 2) {
				$options['keyField'] = array_shift($select);
				$options['valueField'] = array_shift($select) ?: $options['keyField'];
			}
		}

		return parent::findList($query, $options);
	}

	/**
	 * Shortcut method to find a specific entry via primary key.
	 * Wraps Table::get() for an exception free response.
	 *
	 *   $record = $this->Table->record($id);
	 *
	 * @param mixed $id
	 * @param array $options Options for get().
	 * @return mixed The first result from the ResultSet or null if not existent.
	 */
	public function record($id, array $options = []) {
		try {
			return $this->get($id, $options);
		} catch (RecordNotFoundException $e) {
		}
		return null;
	}

	/**
	 * Convenience wrapper of 2.x field() method, but with $options instead.
	 * Do NOT use with 2.x field()s. Make sure those have been replaced to fieldByConditions() instead.
	 *
	 * @param string $name
	 * @param array $options
	 * @return mixed Field value or null if not available
	 */
	public function field($name, array $options = []) {
		return $this->fieldByConditions($name, [], $options);
	}

	/**
	 * Shim of 2.x field() method.
	 *
	 * @param string $name
	 * @param array $conditions
	 * @return mixed Field value or null if not available
	 */
	public function fieldByConditions($name, array $conditions = [], array $customOptions = []) {
		$options = [];
		if ($conditions) {
			$options['conditions'] = $conditions;
		}
		$options += $customOptions;

		$result = $this->find('all', $options)->first();
		if (!$result) {
			return null;
		}
		return $result->get($name);
	}

	/**
	 * 2.x shim for exists() and primary key.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function existsById($id) {
		$conditions = [
			$this->primaryKey() => $id
		];
		return parent::exists($conditions);
	}

	/**
	 * Sets the default ordering as 2.x shim.
	 *
	 * If you don't want that, don't call parent when overwriting it in extending classes.
	 *
	 * @param Event $event
	 * @param Query $query
	 * @param array $options
	 * @param boolean $primary
	 * @return Query
	 */
	public function beforeFind(Event $event, Query $query, $options, $primary) {
		$order = $query->clause('order');
		if (($order === null || !count($order)) && !empty($this->order)) {
			$query->order($this->order);
		}

		return $query;
	}

	/**
	 * Convenience wrapper when upgrading save() from 2.x.
	 *
	 * @param array $entity Data
	 * @param array $options Options
	 * @return \Cake\Datasource\EntityInterface|bool
	 */
	public function saveArray(array $entity, array $options = []) {
		$entity = $this->newEntity($entity);
		return $this->save($entity, $options);
	}

	/**
	 * Convenience wrapper when upgrading saveField() from 2.x.
	 *
	 * @param int $id
	 * @param string $field
	 * @param mixed $value
	 * @return \Cake\Datasource\EntityInterface|bool
	 */
	public function saveField($id, $field, $value) {
		$entity = [
			'id' => $id,
			$field => $value
		];
		return $this->saveArray($entity);
	}

	/**
	 * A 2.x shim of saveAll() wrapping save() calls for multiple entities.
	 *
	 * Wrap it to be transaction safe for all save calls:
	 *
	 *  // In a controller.
	 *  $articles->connection()->transactional(function () use ($articles, $entities) {
	 *      $articles->saveAll($entities, ['atomic' => false]);
	 *  }
	 *
	 * @param array $entities
	 * @param array $options
	 * @return bool True if all save calls where successful
	 */
	public function saveAll(array $entities, array $options = []) {
		$success = true;
		foreach ($entities as $entity) {
			$success = $success & (bool)$this->save($entity, $options);
		}

		return (bool)$success;
	}

	/**
	 * 2.x shim to allow conditions with arrays without explicit IN operator.
	 *
	 * More importantly it fixes a core issue around empty arrays and exceptions
	 * being thrown.
	 *
	 * Please be careful with updating/deleting records and using IN operator.
	 * Especially with NOT involved accidental or injected selection of too many records can easily happen.
	 * Always check the input and maybe add a !empty() protection clause.
	 *
	 * @param string $field
	 * @param array $valueArray
	 * @return array
	 */
	public function arrayConditionArray($field, array $valueArray) {
		$negated = preg_match('/\s+(?:NOT)$/', $field);

		if (count($valueArray) === 0) {
			$condition = '1!=1';
			if ($negated) {
				$condition = '1=1';
			}
			return [$condition];
		}

		return [$field . ' IN' => $valueArray];
	}

	/**
	 * 2.x shim to allow conditions with arrays without explicit IN operator.
	 *
	 * More importantly it fixes a core issue around empty arrays and exceptions
	 * being thrown.
	 *
	 * Please be careful with updating/deleting records and using IN operator.
	 * Especially with NOT involved accidental or injected selection of too many records can easily happen.
	 * Always check the input and maybe add a !empty() protection clause.
	 *
	 * @param \Cake\ORM\Query $query
	 * @param string $field
	 * @param array $valueArray
	 * @return \Cake\ORM\Query
	 */
	public function arrayCondition(Query $query, $field, array $valueArray) {
		if (count($valueArray) === 0) {
			$negated = preg_match('/\s+(?:NOT)$/', $field);
			if ($negated) {
				return $query;
			}
			$query->where('1!=1');

			return $query;
		}

		$query->where([$field . ' IN' => $valueArray]);

		return $query;
	}

	/**
	 * Prefixes the order property with the actual alias if its a string or array.
	 *
	 * The core fails on using the proper prefix when building the query with two
	 * different tables.
	 *
	 * @return void
	 */
	protected function _prefixOrderProperty() {
		if (is_string($this->order)) {
			$this->order = $this->_prefixAlias($this->order);
		}
		if (is_array($this->order)) {
			foreach ($this->order as $key => $value) {
				if (is_numeric($key)) {
					$this->order[$key] = $this->_prefixAlias($value);
				} else {
					$newKey = $this->_prefixAlias($key);
					$this->order[$newKey] = $value;
					if ($newKey !== $key) {
						unset($this->order[$key]);
					}
				}
			}
		}
	}

	/**
	 * Checks if a string of a field name contains a dot if not it will add it and add the alias prefix.
	 *
	 * @param string
	 * @return string
	 */
	protected function _prefixAlias($string) {
		if (strpos($string, '.') === false) {
			return $this->alias() . '.' . $string;
		}
		return $string;
	}

}
