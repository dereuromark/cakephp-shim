<?php

namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BinaryType as CoreBinaryType;
use Cake\Utility\Text;

/**
 * Binary type converter.
 *
 * Used to convert binary values including UUIDs between PHP and the database types.
 *
 * Needs:
 * - \Cake\Database\TypeFactory::map('binary'), 'Shim\Database\Type\BinaryType'); in bootstrap
 */
class BinaryType extends CoreBinaryType {

	/**
	 * Convert binary into resource handles
	 *
	 * @param resource|string $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return resource|string|null
	 */
	public function toPHP(mixed $value, Driver $driver): mixed {
		// Do not convert UUIDs into a resource
		if (is_string($value) && preg_match(
			'/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/i',
			$value,
		)) {
			return $value;
		}

		return parent::toPHP($value, $driver);
	}

	/**
	 * Generate a new UUID
	 *
	 * @return string A new primary key value.
	 */
	public function newId(): string {
		return Text::uuid();
	}

}
