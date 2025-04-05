<?php

namespace Shim\Test\TestCase\View\Helper;

use Cake\Http\ServerRequest;
use Cake\View\View;
use Shim\TestSuite\TestCase;
use Shim\View\Helper\NumberHelper;

class NumberHelperTest extends TestCase {

	protected NumberHelper $Number;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$request = new ServerRequest();
		$this->Number = new NumberHelper(new View($request));
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		unset($this->Table);

		parent::tearDown();
	}

	/**
	 * test format() and empty values
	 * @return void
	 */
	public function testFormatEmpty(): void {
		$value = null;
		$result = $this->Number->format($value);
		$this->assertSame('', $result);

		$result = $this->Number->format($value, ['default' => '-']);
		$this->assertSame('-', $result);

		$value = '';
		$result = $this->Number->format($value);
		$this->assertSame('', $result);
	}

	/**
	 * test currency() and empty values
	 * @return void
	 */
	public function testCurrencyEmpty(): void {
		$value = null;
		$result = $this->Number->currency($value);
		$this->assertSame('', $result);

		$result = $this->Number->currency($value, null, ['default' => '-']);
		$this->assertSame('-', $result);

		$value = '';
		$result = $this->Number->currency($value);
		$this->assertSame('', $result);
	}

}
