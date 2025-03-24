<?php

namespace Shim\Model\Entity;

use ArrayAccess;
use Cake\Datasource\EntityInterface;
use RuntimeException;

/**
 * Trait to require (nested) entity properties in a speaking way up front.
 *
 * - require('supplier.company.state.country') will throw an exception if that nested property is null
 *
 * Note: This is primarily when passing data into a service method and you need to know you passed all contained relations.
 *
 * @mixin \Cake\ORM\Entity
 */
trait RequireTrait {

	use ReadTrait;

	/**
	 * Performant iteration over the path to nullable read the property path.
	 *
	 * Note: Hash::get($this->toArray(), $path, $default); would be simpler, but slower.
	 *
	 * @param array|string $path
	 * @return void
	 */
	public function require($path): void {
		if (!is_array($path)) {
			$parts = explode('.', $path);
		} else {
			$parts = $path;
		}

		$data = null;
		$failed = null;
		foreach ($parts as $key) {
			if ($data === null && $this->$key === null) {
				$failed = $key;

				break;
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

				continue;
			}

			$failed = $key;

			break;
		}

		if ($failed === null) {
			return;
		}

		throw new RuntimeException('Require assertion failed for entity `' . static::class . '` and element `' . $failed . '`: `' . implode('.', $parts) . '`');
	}

}
