<?php

App::uses('FormShimHelper', 'Shim.View/Helper');
App::uses('ShimTestCase', 'Shim.TestSuite');
App::uses('View', 'View');

class FormShimHelperTest extends ShimTestCase {

	public function setUp() {
		$this->Form = new FormShimHelper(new View(null));

		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function testEnd() {
		$result = $this->Form->end();
		$this->assertSame('</form>', $result);
	}

	/**
     * @expectedException PHPUNIT_FRAMEWORK_ERROR_DEPRECATED
	 * @return void
	 */
	public function testEndInvalid() {
		$this->Form->end('Click me');
	}
    
}
