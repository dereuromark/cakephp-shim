<?php
namespace Shim\TestSuite;

use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestCase as CoreIntegrationTestCase;

/**
 * Enhanced IntegrationTestCase backport from 3.x
 */
abstract class IntegrationTestCase extends CoreIntegrationTestCase {

	use TestTrait;

}
