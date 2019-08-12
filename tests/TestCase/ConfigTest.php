<?php

namespace Shim\Test\TestCase;

use Cake\Core\Configure;
use Shim\Config;
use Shim\TestSuite\TestCase;

class ConfigTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp() {
		Configure::delete('Shim.deprecations');

		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		Configure::delete('Shim.deprecations');

		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testDeprecations() {
		$result = Config::deprecations('newEntity');
		$this->assertFalse($result);

		Configure::write('Shim.deprecations.newEntity', true);

		$result = Config::deprecations('newEntity');
		$this->assertTrue($result);
	}

	/**
	 * @return void
	 */
	public function testDeprecationsGlobal() {
		$result = Config::deprecations('newEntity');
		$this->assertFalse($result);

		Configure::write('Shim.deprecations', true);

		$result = Config::deprecations('newEntity');
		$this->assertTrue($result);

		Configure::write('Shim.deprecations.newEntity', false);

		$result = Config::deprecations('newEntity');
		$this->assertFalse($result);
	}

}
