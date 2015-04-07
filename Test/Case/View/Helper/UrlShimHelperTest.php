<?php

App::uses('UrlShimHelper', 'Shim.View/Helper');
App::uses('ShimTestCase', 'Shim.TestSuite');
App::uses('View', 'View');
App::uses('Router', 'Routing');

class UrlShimHelperTest extends ShimTestCase {

	public function setUp() {
		$this->Url = new UrlShimHelper(new View(null));

		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function testBuild() {
		$result = $this->Url->build('/bar');
		$expected = '/bar';
		$this->assertSame($expected, $result);

		$result = $this->Url->build('/bar', true);
		$expected = Router::url('/bar', true);
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testWebroot() {
		$result = $this->Url->webroot('foo');
		$this->assertNotEmpty($result);
	}

	/**
	 * @return void
	 */
	public function testAssetUrl() {
		$result = $this->Url->assetUrl('/xyz.js');
		$this->assertNotEmpty($result);
	}

	/**
	 * @return void
	 */
	public function testAssetTimestamp() {
		$result = $this->Url->assetTimestamp('/xyz.js');
		$this->assertNotEmpty($result);
	}

}
