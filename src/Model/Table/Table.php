<?php

namespace Shim\Model\Table;

use Cake\ORM\Table as CakeTable;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Event\Event;

class Table extends CakeTable {

	public $order = null;

	public $createdField = 'created';

	public $modifiedField = 'modified';

	/**
	 * initialize()
	 *
	 * All models will automatically get Timestamp behavior attached
	 * if created or modified exists.
	 *
	 * @param mixed $config
	 * @return void
	 */
	public function initialize(array $config) {
		// Shims
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
				if (!empty($v['className'])) {
					$v['className'] = Inflector::pluralize($v['className']);
				}
				$v = array_filter($v);
				$this->belongsTo(Inflector::pluralize($k), $v);
			}
		}
		if (!empty($this->hasOne)) {
			foreach ($this->hasOne as $k => $v) {
				if (is_int($k)) {
					$k = $v;
					$v = [];
				}
				if (!empty($v['className'])) {
					$v['className'] = Inflector::pluralize($v['className']);
				}
				$v = array_filter($v);
				$this->hasOne(Inflector::pluralize($k), $v);
			}
		}
		if (!empty($this->hasMany)) {
			foreach ($this->hasMany as $k => $v) {
				if (is_int($k)) {
					$k = $v;
					$v = [];
				}
				if (!empty($v['className'])) {
					$v['className'] = Inflector::pluralize($v['className']);
				}
				$v = array_filter($v);
				$this->hasMany(Inflector::pluralize($k), $v);
			}
		}
		if (!empty($this->hasAndBelongsToMany)) {
			foreach ($this->hasAndBelongsToMany as $k => $v) {
				if (is_int($k)) {
					$k = $v;
					$v = [];
				}
				if (!empty($v['className'])) {
					$v['className'] = Inflector::pluralize($v['className']);
				}
				$v = array_filter($v);
				$this->belongsToMany(Inflector::pluralize($k), $v);
			}
		}
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
				foreach ((array)$rules as $key => $rule) {
					if (isset($rule['required'])) {
						$validator->requirePresence($field, $rule['required']);
						unset($rule['required']);
					}
					if (isset($rule['allowEmpty'])) {
						$validator->allowEmpty($field, $rule['allowEmpty']);
						unset($rule['allowEmpty']);
					}
					if (isset($rule['message'])) {
						$rules[$key]['message'] = __($rule['message']);
					}
				}
				$validator->add($field, $rules);
			}
		}

		return $validator;
	}

	/**
	 * Shim to provide 2.x way of find('first') for easier upgrade.
	 *
	 * @param string $type
	 * @param array $options
	 * @return Query
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

	/**
	 * Shim of 2.x field() method.
	 *
	 * @param string $name
	 * @param array $conditions
	 * @return mixed Field value or null if not available
	 * @deprecated Port to fieldByConditions() instead. Should be a simple str_replace() call.
	 */
	public function field($name, array $conditions = []) {
		trigger_error('field() is deprecated. Use fieldByConditions() instead.', E_USER_DEPRECATED);
		return $this->fieldByConditions($name, $conditions);
	}

	/**
	 * Shim of 2.x field() method.
	 *
	 * @param string $name
	 * @param array $conditions
	 * @return mixed Field value or null if not available
	 */
	public function fieldByConditions($name, array $conditions = []) {
		$options = ['conditions' => $conditions];
		$result = $this->find('all', $options)->first();
		if (!$result) {
			return null;
		}
		return $result->get($name);
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
