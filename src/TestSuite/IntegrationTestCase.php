<?php

namespace Shim\TestSuite;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Enhanced IntegrationTestCase class with debugging tools.
 */
abstract class IntegrationTestCase extends TestCase {

	use IntegrationTestTrait;
	use TestTrait;

}
