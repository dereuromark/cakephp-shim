<?php

namespace Shim\Utility;

use ArrayAccess;
use Cake\Error\Debugger;
use Cake\Http\Exception\BadRequestException;

/**
 * Convenience methods to help for controller's data and other input to assert certain scalar values.
 * - assert{type}() allows nullable returns, otherwise enforces the type
 * - cast{type}() does not allow null, and requires a valid (castable) type
 */
trait CastTrait {

	/**
	 * @param mixed|null $integer
	 *
	 * @return int|null
	 */
	protected function assertInt($integer) {
		if ($integer === null || $integer === '') {
			return null;
		}

		return $this->castInt($integer);
	}

	/**
	 * @param mixed|null $integer
	 *
	 * @throws \Cake\Http\Exception\BadRequestException
	 *
	 * @return int
	 */
	protected function castInt($integer) {
		if ($integer === null || $integer === '') {
			return 0;
		}
		if (!is_numeric($integer)) {
			throw new BadRequestException('The given number is not numeric: ' . Debugger::exportVar($integer));
		}

		return (int)$integer;
	}

	/**
	 * @param mixed|null $float
	 *
	 * @return float|null
	 */
	protected function assertFloat($float) {
		if ($float === null || $float === '') {
			return null;
		}

		return $this->castFloat($float);
	}

	/**
	 * @param mixed|null $float
	 *
	 * @throws \Cake\Http\Exception\BadRequestException
	 *
	 * @return float
	 */
	protected function castFloat($float) {
		if ($float === null || $float === '') {
			return 0;
		}
		if (!is_numeric($float)) {
			throw new BadRequestException('The given number is not numeric: ' . Debugger::exportVar($float));
		}

		return (float)$float;
	}

	/**
	 * @param mixed|null $string
	 *
	 * @return string|null
	 */
	protected function assertString($string) {
		if ($string === null) {
			return $string;
		}

		return $this->castString($string);
	}

	/**
	 * @param mixed|null $string
	 *
	 * @throws \Cake\Http\Exception\BadRequestException
	 *
	 * @return string
	 */
	protected function castString($string) {
		if ($string === null) {
			return '';
		}
		if (!is_scalar($string)) {
			throw new BadRequestException('The given string is not scalar: ' . Debugger::exportVar($string));
		}

		return (string)$string;
	}

	/**
	 * @param mixed|null $boolean
	 *
	 * @return bool|null
	 */
	protected function assertBool($boolean) {
		if ($boolean === null || $boolean === '') {
			return null;
		}

		return $this->castBool($boolean);
	}

	/**
	 * @param mixed|null $boolean
	 *
	 * @throws \Cake\Http\Exception\BadRequestException
	 *
	 * @return bool
	 */
	protected function castBool($boolean) {
		if ($boolean === null) {
			return false;
		}
		if (!is_scalar($boolean)) {
			throw new BadRequestException('The given string is not scalar: ' . Debugger::exportVar($boolean));
		}

		if ($boolean === 'true') {
			return true;
		}
		if ($boolean === 'false') {
			return false;
		}

		return (bool)$boolean;
	}

	/**
	 * @param mixed|null $array
	 *
	 * @throws \Cake\Http\Exception\BadRequestException
	 *
	 * @return array|null
	 */
	protected function assertArray($array) {
		if ($array === null || $array === '') {
			return null;
		}

		return $this->castArray($array);
	}

	/**
	 * @param mixed|null $array
	 *
	 * @throws \Cake\Http\Exception\BadRequestException
	 *
	 * @return array
	 */
	protected function castArray($array) {
		if ($array === null || $array === '') {
			return [];
		}
		if (!is_array($array) && !$array instanceof ArrayAccess) {
			throw new BadRequestException('The given string is not scalar: ' . Debugger::exportVar($array));
		}

		return (array)$array;
	}

}
