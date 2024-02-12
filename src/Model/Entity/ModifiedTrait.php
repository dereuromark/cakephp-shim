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
	 * @param bool $nonStrictComparison For objects and types that would compare false even if equal.
	 *
	 * @return bool
	 */
	public function isModified(string $name, bool $nonStrictComparison = false): bool {
		if (!$this->isDirty($name)) {
			return false;
		}

		$value = $this->get($name);
		if (
			!in_array($name, $this->_originalFields, true) ||
			(
				array_key_exists($name, $this->_original) &&
				($this->_original[$name] !== $value || $nonStrictComparison && $this->_original[$name] != $value)
			)
		) {
			return true;
		}

		return false;
	}

	/**
	 * Returns all fields that actually changed their value, despite dirty state (touched).
	 *
	 * @param bool $nonStrictComparison
	 *
	 * @return array<string>
	 */
	public function getModifiedFields(bool $nonStrictComparison = false): array {
		$modified = [];

		$touched = $this->getDirty();
		foreach ($touched as $field) {
			if (!$this->isModified($field, $nonStrictComparison)) {
				continue;
			}

			$modified[] = $field;
		}

		return $modified;
	}

}
