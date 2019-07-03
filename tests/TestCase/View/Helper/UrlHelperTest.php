<?php
namespace Shim\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Shim\View\Helper\UrlHelper;

/**
 * UrlHelperTest class
 */
class UrlHelperTest extends TestCase {

	/**
	 * @var \Cake\View\View
	 */
	protected $View;

	/**
	 * @var \Shim\View\Helper\UrlHelper
	 */
	protected $Url;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->View = new View();
		$this->Url = new UrlHelper($this->View);
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		unset($this->View, $this->Url);
		parent::tearDown();
	}

	/**
	 * testRead method
	 *
	 * @return void
	 */
	public function testBuild() {
		$url = '/';
		$options = [
			'fullBase' => true,
		];
		$result = $this->Url->build($url, $options);
		$this->assertEquals('/', $result);
	}

}
