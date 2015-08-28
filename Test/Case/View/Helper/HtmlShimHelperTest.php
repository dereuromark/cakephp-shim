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

	/**
	 * @return void
	 */
	public function testCss() {
		$result = $this->Html->css('foo/bar.css', ['rel' => 'baz']);
		$expected = '<link rel="baz" type="text/css" href="/css/foo/bar.css"/>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @expectedException PHPUNIT_FRAMEWORK_ERROR_DEPRECATED
	 * @return void
	 */
	public function testCssInvalid() {
		$this->Html->css('foo/bar.css', 'baz');
	}

	/**
	 * @return void
	 */
	public function testCssInvalidConfirmBC() {
	   $this->Html = new HtmlShimTestHelper(new View(null));
		$result = $this->Html->css('foo/bar.css', 'baz');
		$expected = '<link rel="baz" type="text/css" href="/css/foo/bar.css"/>';
		$this->assertSame($expected, $result);
	}

}

class HtmlShimTestHelper extends HtmlShimHelper {

	public function css($path, $options = []) {
		if (!is_array($options)) {
			$rel = $options;
			$options = [];
			if ($rel) {
				$options['rel'] = $rel;
			}
			if (func_num_args() > 2) {
				$options = func_get_arg(2) + $options;
			}
			unset($rel);

			//trigger_error('The second argument needs to be an array. Use `rel` key in $options instead.', E_USER_DEPRECATED);
		}
		return parent::css($path, $options);
	}

}
