<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.6.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Shim\Database\Type;

use Cake\Core\Exception\Exception;
use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\Database\TypeInterface;
use Cake\Utility\Text;
use PDO;

/**
 * Binary UUID type converter.
 *
 * Use to convert binary UUID data between PHP and the database types.
 *
 * This is a port of 3.6 type class for 3.x usage until it lands stable.
 * - Type::map('uuid', 'Shim\Database\Type\BinaryUuidType'); in bootstrap
 */
class BinaryUuidType extends Type implements TypeInterface {

	/**
	 * Convert binary UUID data into the database format.
	 *
	 * Binary data is not altered before being inserted into the database.
	 * As PDO will handle reading file handles.
	 *
	 * @param string|resource $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return string|resource
	 */
	public function toDatabase($value, Driver $driver) {
		if (is_string($value)) {
			return $this->convertStringToBinaryUuid($value);
		}

		return $value;
	}

	/**
	 * Generate a new binary UUID
	 *
	 * @return string A new primary key value.
	 */
	public function newId() {
		return Text::uuid();
	}

	/**
	 * Convert binary UUID into resource handles
	 *
	 * @param resource|string|null $value The value to convert.
	 * @param \Cake\Database\Driver $driver The driver instance to convert with.
	 * @return resource|string|null
	 * @throws \Cake\Core\Exception\Exception
	 */
	public function toPHP($value, Driver $driver) {
		if ($value === null) {
			return null;
		}
		if (is_string($value)) {
			return $this->convertBinaryUuidToString($value);
		}
		if (is_resource($value)) {
			return $value;
		}

		throw new Exception(sprintf('Unable to convert value of type %s into binary UUID.', gettype($value)));
	}

	/**
	 * Get the correct PDO binding type for Binary data.
	 *
	 * @param mixed $value The value being bound.
	 * @param \Cake\Database\Driver $driver The driver.
	 * @return int
	 */
	public function toStatement($value, Driver $driver) {
		return PDO::PARAM_LOB;
	}

	/**
	 * Marshalls flat data into PHP objects.
	 *
	 * Most useful for converting request data into PHP objects
	 * that make sense for the rest of the ORM/Database layers.
	 *
	 * @param mixed $value The value to convert.
	 *
	 * @return mixed Converted value.
	 */
	public function marshal($value) {
		return $value;
	}

	/**
	 * Converts a binary UUID to a string representation.
	 *
	 * @param string $binary The value to convert.
	 *
	 * @return string Converted value.
	 */
	protected function convertBinaryUuidToString($binary) {
		$string = unpack('H*', $binary);

		$string = preg_replace(
			'/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/',
			'$1-$2-$3-$4-$5',
			$string
		);

		return $string[1];
	}

	/**
	 * Converts a string UUID to a binary representation.
	 *
	 * @param string $string The value to convert.
	 *
	 * @return string Converted value.
	 */
	protected function convertStringToBinaryUuid($string) {
		$string = str_replace('-', '', $string);

		return pack('H*', $string);
	}

}
