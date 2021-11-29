<?php

namespace Shim\Model\Entity;

use Cake\Utility\Inflector;
use RuntimeException;

/**
 * Trait to read entity properties in a way that the return value is ensured.
 *
 * - get{PropertyName}OrFail() must return the property or throws exception otherwise
 */
trait SetTrait {

	/**
	 * @param string $property
	 * @param mixed $value
	 * @throws \RuntimeException
	 * @return $this
	 */
	public function setOrFail(string $property, $value) {
		if ($value === null) {
			throw new RuntimeException('$' . $property . ' is null, expected non-null value.');
		}

		$this->$property = $value;

		return $this;
	}

	/**
	 * @param string $name
	 * @param array $arguments
	 * @throws \RuntimeException
	 * @return $this
	 */
	public function __call(string $name, array $arguments) {
		if (!preg_match('/^set([A-Z][A-Za-z0-9]+)OrFail$/', $name, $matches)) {
			throw new RuntimeException('Method ' . $name . ' cannot be found; set{PropertyName}OrFail() expected.');
		}
		if (!$arguments) {
			throw new RuntimeException('Method ' . $name . ' param for value not found, but expected.');
		}

		$property = Inflector::underscore($matches[1]);

		$this->setOrFail($property, $arguments[1]);

		return $this;
	}

}
