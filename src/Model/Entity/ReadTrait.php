<?php

namespace Shim\Model\Entity;

use ArrayAccess;
use Cake\Datasource\EntityInterface;

/**
 * Trait to read entity properties in a way that it doesnt throw exception if parts of the path are null.
 *
 * - read('tags.0.name') will return the property if it exists or returns null otherwise
 *
 * Note: This is primarily for convenience usage in view templates, as the return type cannot be annotated here.
 *
 * @mixin \Cake\ORM\Entity
 */
trait ReadTrait {

	/**
	 * Performant iteration over the path to nullable read the property path.
	 *
	 * Note: Hash::get($this->toArray(), $path, $default); would be simpler, but slower.
	 *
	 * @param string|array $path
	 * @param mixed $default The return value when the path does not exist
	 * @return mixed|null The value fetched from the entity, or null.
	 */
	public function read($path, $default = null) {
		if (!is_array($path)) {
			$parts = explode('.', $path);
		} else {
			$parts = $path;
		}

		$data = null;
		foreach ($parts as $key) {
			if ($data === null && !isset($this->$key)) {
				return $default;
			}
			if ($data === null) {
				$data = $this->$key;
				continue;
			}

			if ($data instanceof EntityInterface) {
				$data = $data->toArray();
			}

			if ((is_array($data) || $data instanceof ArrayAccess) && isset($data[$key])) {
				$data = $data[$key];
			} else {
				return $default;
			}
		}

		return $data;
	}

}
