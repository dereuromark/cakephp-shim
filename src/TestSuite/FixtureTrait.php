<?php

namespace Shim\TestSuite;

use RuntimeException;

/**
 * FC shim for TestCase::addFixture()
 *
 * @mixin \Cake\TestSuite\TestCase
 */
trait FixtureTrait {

	/**
	 * Adds a fixture to this test case.
	 *
	 * Examples:
	 * - core.Tags
	 * - app.MyRecords
	 * - plugin.MyPluginName.MyModelName
	 *
	 * @param string $fixture Fixture
	 * @return $this
	 */
	protected function addFixture($fixture) {
		if (!isset($this->fixtures)) {
			$this->fixtures = [];
		}
		if (is_string($this->fixtures)) {
			throw new RuntimeException('You must be using type array for $fixture property.');
		}

		$this->fixtures[] = $fixture;

		return $this;
	}

}
