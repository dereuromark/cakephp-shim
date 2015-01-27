<?php
App::uses('Model', 'Model');

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
	}

	/**
	 * Detect missing contain in querys to help getting rid of recursive > -1 globally
	 *
	 * @param string $type
	 * @param array $query
	 * @return mixed
	 */
	public function find($type = 'first', $query = array()) {
		if ((bool)Configure::read('App.warnAboutMissingContain') !== false) {
			if ($this->alias !== 'Session' && $this->recursive !== -1 && !isset($query['contain'])) {
				$message = 'No recursive -1 or contain used for this query in ' . $this->alias . ': ' . print_r($query, true);
				if (Configure::read('debug')) {
					throw new CakeException($message);
				}
				trigger_error($message, E_USER_WARNING);
			}
		}
		return parent::find($type, $query);
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
	 *    be returned. If no validation key is provided, defaults to true.
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

}
