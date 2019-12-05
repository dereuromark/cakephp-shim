<?php

namespace Shim\Model\Entity;

use Cake\Utility\Inflector;
use RuntimeException;

/**
 * Trait to read entity properties in a way that the return value is ensured.
 *
 * - get{PropertyName}OrFail() must return the property or throws exception otherwise
 */
trait GetTrait {

	/**
	 * @param string $property
	 * @return mixed
	 * @throws \RuntimeException
	 */
	public function getOrFail($property) {
		if (!isset($this->$property)) {
			throw new RuntimeException('$' . $property . ' is null, expected non-null value.');
		}

		return $this->$property;
	}

	/**
	 * @param string $name
	 * @param array $arguments
	 * @return mixed
	 * @throws \RuntimeException
	 */
	public function __call($name, array $arguments) {
		if (!preg_match('/get([A-Z][A-Za-z0-9]+)OrFail/', $name, $matches)) {
			throw new RuntimeException('Method ' . $name . ' cannot be found; get{PropertyName}OrFail() expected.');
		}

		$property = Inflector::underscore($matches[1]);

		return $this->getOrFail($property);
	}

}
