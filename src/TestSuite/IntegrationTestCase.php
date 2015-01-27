<?php
namespace Shim\TestSuite;

use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestCase as CakeIntegrationTestCase;

/**
 * Enhanced IntegrationTestCase backport from 3.x
 */
abstract class IntegrationTestCase extends CakeIntegrationTestCase {

	use TestTrait;

}
