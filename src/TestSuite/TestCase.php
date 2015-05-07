<?php
namespace Shim\TestSuite;

use Cake\TestSuite\TestCase as CoreTestCase;

/**
 * Enhanced TestCase class
 */
abstract class TestCase extends CoreTestCase {

	use TestTrait;

}
