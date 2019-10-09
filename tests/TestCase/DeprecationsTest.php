<?php

namespace Shim\Test\TestCase;

use Cake\Core\Configure;
use Shim\Deprecations;
use Shim\TestSuite\TestCase;

class DeprecationsTest extends TestCase {

	/**
	 * @return void
	 */
	public function setUp(): void {
		Configure::delete('Shim.deprecations');

		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		Configure::delete('Shim.deprecations');

		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testDeprecations() {
		$result = Deprecations::enabled('newEntity');
		$this->assertFalse($result);

		Configure::write('Shim.deprecations.newEntity', true);

		$result = Deprecations::enabled('newEntity');
		$this->assertTrue($result);
	}

	/**
	 * @return void
	 */
	public function testDeprecationsGlobal() {
		$result = Deprecations::enabled('newEntity');
		$this->assertFalse($result);

		Configure::write('Shim.deprecations', true);

		$result = Deprecations::enabled('newEntity');
		$this->assertTrue($result);

		Configure::write('Shim.deprecations.newEntity', false);

		$result = Deprecations::enabled('newEntity');
		$this->assertFalse($result);
	}

}
