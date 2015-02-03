<?php

App::uses('HtmlShimHelper', 'Shim.View/Helper');
App::uses('ShimTestCase', 'Shim.TestSuite');
App::uses('View', 'View');

class HtmlShimHelperTest extends ShimTestCase {

	public function setUp() {
		$this->Html = new HtmlShimHelper(new View(null));

		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function testLink() {
		$result = $this->Html->link('Foo', '/bar', ['confirm' => 'Some string here']);
        $expected = '<a href="/bar" onclick="if (confirm(&quot;Some string here&quot;)) { return true; } return false;">Foo</a>';
		$this->assertSame($expected, $result);
	}

	/**
     * @expectedException PHPUNIT_FRAMEWORK_ERROR_DEPRECATED
	 * @return void
	 */
	public function testLinkInvalid() {
		$this->Html->link('Foo', '/bar', [], 'Some string here');
	}
    
}
