<?php

namespace Shim\TestSuite;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Enhanced IntegrationTestCase class with debugging tools.
 */
abstract class IntegrationTestCase extends TestCase {

	use IntegrationTestTrait;
	use TestTrait {
		IntegrationTestTrait::isDebug insteadof TestTrait;
	}

	/**
	 * Globally disabling error handler middleware to see the actual errors instead of cloaking.
	 *
	 * You can enable this when you don't explicitly test exception handling for controllers.
	 */
	protected bool $disableErrorHandlerMiddleware = false;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		if (!$this->disableErrorHandlerMiddleware) {
			return;
		}
		$this->disableErrorHandlerMiddleware();
	}

}
