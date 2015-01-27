<?php
namespace Shim\TestSuite;

use Cake\TestSuite\TestCase as CakeTestCase;

/**
 * Enhanced TestCase class
 */
abstract class TestCase extends CakeTestCase {

	use TestTrait;

}
