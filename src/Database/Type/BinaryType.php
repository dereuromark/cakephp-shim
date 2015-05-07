<?php
namespace Shim\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BinaryType as CoreBinaryType;
use Cake\Utility\Text;

/**
 * Binary type converter.
 *
 * Use to convert binary values including UUIDs between PHP and the database types.
 */
class BinaryType extends CoreBinaryType {

	/**
	 * Convert binary into resource handles
	 *
	 * @param null|string|resource $value The value to convert.
	 * @param Driver $driver The driver instance to convert with.
	 * @return resource|null
	 * @throws \Cake\Core\Exception\Exception
	 */
	public function toPHP($value, Driver $driver) {
		// Do not convert UUIDs into a resource
		if (is_string($value) && preg_match(
				'/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/i',
				$value
			)
		) {
			return $value;
		}

		return parent::toPHP($value, $driver);
	}

	/**
	 * Generate a new UUID
	 *
	 * @return string A new primary key value.
	 */
	public function newId() {
		return Text::uuid();
	}

}
