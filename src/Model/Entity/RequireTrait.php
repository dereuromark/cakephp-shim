<?php

namespace Shim\Model\Entity;

use RuntimeException;

/**
 * Trait to require entity properties in a way that the value is ensured (not nullable).
 */
trait RequireTrait {

	/**
	 * @param string $property
	 * @throws \RuntimeException
	 * @return void
	 */
	public function require(string $property): void {
		if (!isset($this->$property)) {
			throw new RuntimeException('$' . $property . ' is null, expected non-null value.');
		}
	}

}
