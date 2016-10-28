<?php

namespace Tools\Test\TestCase\View\Helper;

use Cake\View\View;
use Cake\TestSuite\TestCase;
use Shim\View\Helper\JsHelper;

class JsHelperTest extends TestCase {

	/**
	 * @var \Shim\View\Helper\JsHelper
	 */
	public $Js;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->Js = new JsHelper(new View(null));
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		unset($this->Table);

 		//TableRegistry::clear();
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testObject() {
		$this->assertInstanceOf(JsHelper::class, $this->Js);
	}

	/**
	 * JsHelperTest::testBuffer()
	 *
	 * @return void
	 */
	public function testBuffer() {
		$script = <<<JS
jQuery(document).ready(function() {
	// Code
});
JS;
		$this->Js->buffer($script);

		$output = $this->Js->writeBuffer();

		$expected = <<<HTML
<script>
//<![CDATA[
jQuery(document).ready(function() {
	// Code
});
//]]>
</script>
HTML;
		$this->assertTextEquals($expected, $output);
	}

}
