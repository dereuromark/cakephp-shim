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

	/**
	 * Globally disabling error handler middleware to see the actual errors instead of cloaking.
	 *
	 * You can enable this when you don't explicitly test exception handling for controllers.
	 *
	 * @var bool
	 */
	protected $disableErrorHandlerMiddleware = false;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		if (!$this->disableErrorHandlerMiddleware) {
			return;
		}
		$this->disableErrorHandlerMiddleware();
	}

}
