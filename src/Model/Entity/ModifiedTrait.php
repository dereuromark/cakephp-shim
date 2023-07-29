<?php

namespace Shim\Model\Entity;

/**
 * Trait to get actually changed entity properties.
 */
trait ModifiedTrait {

	/**
	 * Returns if this field actually changed its value, despite dirty state (touched).
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isModified(string $name): bool {
		$value = $this->get($name);
		if (
			!array_key_exists($name, $this->_original)
			|| $this->_original[$name] !== $value
		) {
			return true;
		}

		return false;
	}

	/**
	 * Returns all fields that actually changed their value, despite dirty state (touched).
	 *
	 * @return array<string>
	 */
	public function getModifiedFields(): array {
		$modified = [];

		$touched = $this->getDirty();
		foreach ($touched as $field) {
			if (!$this->isModified($field)) {
				continue;
			}

			$modified[] = $field;
		}

		return $modified;
	}

}
