<?php
namespace Shim\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Utility\Hash;

/**
 * A behavior to assert data consistency regarding empty values.
 * It will check the schema and depending on nullable type or not transform empty string input
 * (from forms for example) into `null`.
 */
class NullableBehavior extends Behavior {

	/**
	 * @param \Cake\Event\Event $event
	 * @param \ArrayObject $data
	 * @param \ArrayObject $options
	 * @return void
	 */
	public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
		$this->_process($data, $this->_table);
	}

	/**
	 * @param \ArrayObject $data
	 * @param \Cake\ORM\Table $table
	 * @return \ArrayObject
	 */
	protected function _process($data, Table $table) {
		$associations = [];
		/* @var \Cake\ORM\Association $association */
		foreach ($table->associations() as $association) {
			$associations[$association->property()] = $association->name();
		}

		foreach ($data as $key => $value) {
			if (array_key_exists($key, $associations)) {
				$data[$key] = $this->_process($data[$key], $table->association($associations[$key])->target());
				continue;
			}
			$nullable = Hash::get((array)$table->schema()->column($key), 'null');
			if ($nullable !== true) {
				continue;
			}
			if ($value !== '') {
				continue;
			}

			$default = Hash::get((array)$table->schema()->column($key), 'default');
			$data[$key] = $default;
		}

		return $data;
	}

}
