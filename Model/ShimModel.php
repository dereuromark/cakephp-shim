<?php
App::uses('Model', 'Model');
App::uses('RecordNotFoundException', 'Shim.Error');
App::uses('ShimException', 'Shim.Error');

/**
 * Model enhancements for Cake2
 *
 * @author Mark Scherer
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class ShimModel extends Model {

	public $recursive = -1;

	public $actsAs = ['Containable'];

	/**
	 * MyModel::__construct()
	 *
	 * @param int $id
	 * @param string $table
	 * @param string $ds
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		if (!Configure::read('Model.disablePrefixing')) {
			$this->prefixOrderProperty();
		}

		$event = new CakeEvent('Model.initialize', $this, compact('id', 'table', 'ds'));
		$this->getEventManager()->dispatch($event);
	}

	/**
	 * Implemented events.
	 *
	 * @return array.
	 */
	public function implementedEvents() {
		return (array_merge(parent::implementedEvents(), array(
			'Model.initialize' => 'initialize',
		)));
	}

	/**
	 * Detect missing contain in querys to help getting rid of recursive > -1 globally
	 *
	 * @param string $type
	 * @param array $query
	 * @return mixed
	 */
	public function find($type = 'first', $query = []) {
		if ($warn = Configure::read('Shim.warnAboutMissingContain')) {
			if ($this->alias !== 'Session' && $this->recursive !== -1 && (!isset($query['recursive']) || (int)$query['recursive'] !== -1) && !isset($query['contain'])) {
				$message = 'No recursive -1 or contain used for the query in ' . $this->alias;
				if (Configure::read('debug') && $warn === 'exception') {
					throw new ShimException($message, 500, $query);
				}
				$message .= ': ' . print_r($query, true);
				trigger_error($message, E_USER_WARNING);
			}
		}
		return parent::find($type, $query);
	}

	/**
	 * Overwrite field to automatically add contain() to avoid above `Shim.warnAboutMissingContain`
	 * notices here. Also triggers deprecation notice if `Shim.deprecateField` is enabled as this method
	 * will not be directly 3.x compatible without the 3.x shim pendant.
	 *
	 * @param mixed $name
	 * @param mixed $conditions
	 * @param mixed $order
	 * @return void
	 */
	public function field($name, $conditions = null, $order = null) {
		if ($conditions === null && $this->id !== false) {
			if (Configure::read('Shim.deprecateField')) {
				trigger_error('Using implicit Model->id is deprecated in shim context. Pass it as conditions part instead.', E_USER_DEPRECATED);
			}
			$conditions = array($this->alias . '.' . $this->primaryKey => $this->id);
		}
		if (Configure::read('Shim.deprecateField')) {
			trigger_error('field() is deprecated in the shim context. Use shimmed fieldByConditions() or find() instead.', E_USER_DEPRECATED);
		}
		$options = ['fields' => $name];
		if ($order !== null) {
			$options['order'] = $order;
		}
		return $this->fieldByConditions($name, (array)$conditions, $options);
	}

	/**
	 * Shim of 2.x field() method with the possibility of providing contain, recursive and other
	 * options keys along with it.
	 *
	 * @param string $name
	 * @param array $conditions
	 * @param array $options Custom options (order, contain, ...)
	 * @return mixed Field value or false if not found.
	 */
	public function fieldByConditions($name, array $conditions = [], array $customOptions = []) {
		$options = ['conditions' => $conditions];
		$customOptions += ['contain' => []];
		$options += $customOptions;

		$data = $this->find('first', $options);
		if (!$data) {
			return false;
		}

		if (strpos($name, '.') === false) {
			if (isset($data[$this->alias][$name])) {
				return $data[$this->alias][$name];
			}
		} else {
			$name = explode('.', $name);
			if (isset($data[$name[0]][$name[1]])) {
				return $data[$name[0]][$name[1]];
			}
		}

		if (isset($data[0]) && count($data[0]) > 0) {
			return array_shift($data[0]);
		}
		return null;
	}

	/**
	 * Saves the value of a single field to the database, based on the current
	 * model ID.
	 *
	 * @param string $name Name of the table field
	 * @param mixed $value Value of the field
	 * @param bool|array $validate Either a boolean, or an array.
	 *   If a boolean, indicates whether or not to validate before saving.
	 *   If an array, allows control of 'validate', 'callbacks' and 'counterCache' options.
	 *   See Model::save() for details of each options.
	 * @return bool|array See Model::save() False on failure or an array of model data on success.
	 */
	public function saveField($name, $value, $validate = false) {
		if (Configure::read('Shim.deprecateSaveField')) {
			trigger_error('Deprecated in the shim context. Please use save() or updateAll() directly.', E_USER_DEPRECATED);
		}
		return parent::saveField($name, $value, $validate);
	}

	/**
	 * Prefixes the order property with the actual alias if its a string or array.
	 *
	 * The core fails on using the proper prefix when building the query with two
	 * different tables.
	 *
	 * @return void
	 */
	public function prefixOrderProperty() {
		if (is_string($this->order)) {
			$this->order = $this->prefixAlias($this->order);
		}
		if (is_array($this->order)) {
			foreach ($this->order as $key => $value) {
				if (is_numeric($key)) {
					$this->order[$key] = $this->prefixAlias($value);
				} else {
					$newKey = $this->prefixAlias($key);
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
	public function prefixAlias($string) {
		if (strpos($string, '.') === false) {
			return $this->alias . '.' . $string;
		}
		return $string;
	}

	/**
	 * Deconstructs a complex data type (array or object) into a single field value.
	 * BUGFIXED VERSION - autodetects type and allows manual override
	 *
	 * @param string $field The name of the field to be deconstructed
	 * @param array|object $data An array or object to be deconstructed into a field
	 * @return mixed The resulting data that should be assigned to a field
	 */
	public function deconstruct($field, $data, $type = null) {
		if (!is_array($data)) {
			return $data;
		}
		if ($type === null) {
			$type = $this->getColumnType($field);
		}
		if ($type === null) {
			//try to autodetect
			if (isset($data['day']) || isset($data['month']) || isset($data['year'])) {
				$type = 'date';
			}
			if (isset($data['hour']) || isset($data['min']) || isset($data['sec'])) {
				$type .= 'time';
			}
		}

		if (in_array($type, ['datetime', 'timestamp', 'date', 'time'])) {
			$useNewDate = (isset($data['year']) || isset($data['month']) ||
				isset($data['day']) || isset($data['hour']) || isset($data['minute']));

			$dateFields = ['Y' => 'year', 'm' => 'month', 'd' => 'day', 'H' => 'hour', 'i' => 'min', 's' => 'sec'];
			$timeFields = ['H' => 'hour', 'i' => 'min', 's' => 'sec'];
			$date = [];

			if (isset($data['meridian']) && empty($data['meridian'])) {
				return null;
			}

			if (
				isset($data['hour']) &&
				isset($data['meridian']) &&
				!empty($data['hour']) &&
				$data['hour'] != 12 &&
				'pm' == $data['meridian']
			) {
				$data['hour'] = $data['hour'] + 12;
			}
			if (isset($data['hour']) && isset($data['meridian']) && $data['hour'] == 12 && 'am' == $data['meridian']) {
				$data['hour'] = '00';
			}
			if ($type === 'time') {
				foreach ($timeFields as $key => $val) {
					if (!isset($data[$val]) || $data[$val] === '0' || $data[$val] === '00') {
						$data[$val] = '00';
					} elseif ($data[$val] !== '') {
						$data[$val] = sprintf('%02d', $data[$val]);
					}
					if (!empty($data[$val])) {
						$date[$key] = $data[$val];
					} else {
						return null;
					}
				}
			}

			if ($type === 'datetime' || $type === 'timestamp' || $type === 'date') {
				foreach ($dateFields as $key => $val) {
					if ($val === 'hour' || $val === 'min' || $val === 'sec') {
						if (!isset($data[$val]) || $data[$val] === '0' || $data[$val] === '00') {
							$data[$val] = '00';
						} else {
							$data[$val] = sprintf('%02d', $data[$val]);
						}
					}
					if (!isset($data[$val]) || isset($data[$val]) && (empty($data[$val]) || $data[$val][0] === '-')) {
						return null;
					}
					if (isset($data[$val]) && !empty($data[$val])) {
						$date[$key] = $data[$val];
					}
				}
			}

			if ($useNewDate && !empty($date)) {
				$format = $this->getDataSource()->columns[$type]['format'];
				foreach (['m', 'd', 'H', 'i', 's'] as $index) {
					if (isset($date[$index])) {
						$date[$index] = sprintf('%02d', $date[$index]);
					}
				}
				return str_replace(array_keys($date), array_values($date), $format);
			}
		}
		return $data;
	}

	/**
	 * Override default updateAll to workaround forced joins.
	 *
	 * This is a shim method to more easily migrate to 3.x as there
	 * updateAll() does not allow joining anymore.
	 *
	 * @param array $fields Set of fields and values, indexed by fields.
	 *   Fields are treated as SQL snippets, to insert literal values manually escape your data.
	 * @param mixed $conditions Conditions to match, true for all records
	 * @return bool True on success, false on failure
	 */
	public function updateAllJoinless($fields, $conditions = true) {
		$name = $this->name;
		$this->name = '_model_';

		try {
			$result = $this->updateAll($fields, $conditions);
		} catch (Exception $e) {
			$this->name = $name;
			throw $e;
		}

		$this->name = $name;
		return $result;
	}

	/**
	 * Override default deleteAll to workaround forced joins
	 *
	 * This is a shim method to more easily migrate to 3.x as there
	 * deleteAll() does not allow joining anymore.
	 *
	 * @param mixed $conditions Conditions to match
	 * @param bool $cascade Set to true to delete records that depend on this record
	 * @param bool $callbacks Run callbacks
	 * @return bool True on success, false on failure
	 */
	public function deleteAllJoinless($conditions, $dependent = true, $callbacks = false) {
		$associated = [];
		foreach ($this->getAssociated() as $model => $type) {
			$associated[$type][] = $model;
		}

		$this->unbindModel($associated);

		return $this->deleteAll($conditions, $dependent, $callbacks);
	}

	/**
	 * Delete all records using an atomic query similar to updateAll().
	 * Note: Does not need manual sanitizing/escaping, though.
	 *
	 * Does not do any callbacks
	 *
	 * @param mixed $conditions Conditions to match, true for all records
	 * @return bool Success
	 */
	public function deleteAllRaw($conditions = true) {
		return $this->getDataSource()->delete($this, $conditions);
	}

	/**
	 * Overwrite invalidate to allow last => true
	 *
	 * @param string $field The name of the field to invalidate
	 * @param mixed $value Name of validation rule that was not failed, or validation message to
	 *   be returned. If no validation key is provided, defaults to true.
	 * @param bool $last If this should be the last validation check for this validation run
	 * @return void
	 */
	public function invalidate($field, $value = true, $last = false) {
		parent::invalidate($field, $value);
		if (!$last) {
			return;
		}

		$this->validator()->remove($field);
	}

	/**
	 * Shortcut method to find a specific entry via primary key.
	 * This has the same behavior as in 3.x.
	 *
	 * It is best to pass the id directly:
	 *
	 *   $record = $this->Model->get($id);
	 *
	 * @param mixed $id
	 * @param array $options Options for find().
	 * @return mixed
	 * @throws RecordNotFoundException If record not found.
	 */
	public function get($id, array $options = []) {
		if (is_array($id)) {
			$column = $id[0];
			$value = $id[1];
		} else {
			$column = $this->primaryKey;
			$value = $id;
			if ($value === null) {
				$value = $this->id;
			}
		}
		if (!$value) {
			return [];
		}

		if (!isset($options['contain'])) {
			$options['contain'] = [];
		}
		if (!isset($options['conditions'])) {
			$options['conditions'] = [];
		}
		$options['conditions'] = array_merge($options['conditions'], [$this->alias . '.' . $column => $value]);

		$result = $this->find('first', $options);
		if (!$result && empty($options['noException'])) {
			throw new RecordNotFoundException(sprintf(
				'Record not found in model "%s"',
				$this->alias
			));
		}
		return $result;
	}

	/**
	 * Shortcut method to find a specific entry via primary key.
	 * Wraps ShimModel::get() for an exception free response.
	 * This eases upgrading to 3.x as it can then be quicker replaced.
	 *
	 *   $record = $this->Model->record($id);
	 *
	 * @param mixed $id
	 * @param array $options Options for find().
	 * @return array
	 */
	public function record($id, array $options = []) {
		$options += ['noException' => true];
		return $this->get($id, $options);
	}

	/**
	 * Model initialization callback. To avoid overwriting constructor
	 *
	 * Will have param `array $config` in 3.x.
	 *
	 * @return void
	 */
	public function initialize($id = false, $table = null, $ds = null) {
	}

	/**
	 * @param string $behavior
	 * @param array $options
	 * @return bool
	 */
	public function addBehavior($behavior, array $options = []) {
		return $this->Behaviors->load($behavior, $options);
	}

	/**
	 * Removes a behavior from this table's behavior registry.
	 *
	 * Example:
	 *
	 * Remove a behavior from this table.
	 *
	 * ```
	 * $this->removeBehavior('Tree');
	 * ```
	 *
	 * @param string $name The alias that the behavior was added with.
	 * @return void
	 */
	public function removeBehavior($name) {
		$this->Behaviors->unload($name);
	}

	/**
	 * Returns the behavior registry for this table.
	 *
	 * @return BehaviorRegistry
	 */
	public function behaviors() {
		return $this->Behaviors;
	}

	/**
	 * Check if a behavior with the given alias has been loaded.
	 *
	 * @param string $name The behavior alias to check.
	 * @return array
	 */
	public function hasBehavior($name) {
		return $this->Behaviors->loaded($name);
	}

	protected function _setAssoc($type, $name, $options = []) {
		$this->bindModel(array(
			$type => array(
				$name => $options
			)
		), true);
	}

	/**
	 * @param string $associated
	 * @param array $options
	 * @return void
	 */
	public function hasOne($associated, array $options = []) {
		$this->_setAssoc('hasOne', $associated, $options);
	}

	/**
	 * @param string $associated
	 * @param array $options
	 * @return void
	 */
	public function hasMany($associated, array $options = []) {
		$this->_setAssoc('hasMany', $associated, $options);
	}

	/**
	 * @param string $associated
	 * @param array $options
	 * @return void
	 */
	public function belongsToMany($associated, array $options = []) {
		$this->_setAssoc('hasAndBelongsToMany', $associated, $options);
	}

	/**
	 * @param string $associated
	 * @param array $options
	 * @return void
	 */
	public function belongsTo($associated, array $options = []) {
		$this->_setAssoc('belongsTo', $associated, $options);
	}

	/**
	 * Sets/Gets primary key
	 *
	 * @param string|null
	 * @return string
	 */
	public function primaryKey($primaryKey = null) {
		if (!empty($primaryKey)) {
			$this->primaryKey = $primaryKey;
		}
		return $this->primaryKey;
	}

	/**
	 * Sets/Gets used table
	 *
	 * @param string|null
	 * @return string
	 */
	public function table($table = null) {
		if (!empty($table)) {
			$this->useTable = $table;
		}
		return $this->useTable;
	}

	/**
	 * Sets/Gets used table
	 *
	 * @param string|null
	 * @return string
	 */
	public function displayField($key = null) {
		if (!empty($key)) {
			$this->displayField = $key;
		}
		return $this->displayField;
	}

	/**
	 * Returns the table alias or sets a new one
	 *
	 * @param string|null $alias the new table alias
	 * @return string
	 */
	public function alias($alias = null) {
		if (!empty($alias)) {
			$this->alias = $alias;
		}
		return $this->alias;
	}

}
