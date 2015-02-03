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
	public function testPostLink() {
		$result = $this->Form->postLink('Foo', '/bar', ['confirm' => 'Some string here']);
		$expected = '<a href="#" onclick="if (confirm(&quot;Some string here&quot;)) { document';
		$this->assertContains($expected, $result);
	}

	/**
	 * @expectedException PHPUNIT_FRAMEWORK_ERROR_DEPRECATED
	 * @return void
	 */
	public function testPostLinkInvalid() {
		$this->Form->postLink('Foo', '/bar', [], 'Some string here');
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
