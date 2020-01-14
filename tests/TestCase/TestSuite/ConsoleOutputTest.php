<?php

namespace Shim\Test\TestCase\TestSuite;

use Shim\TestSuite\ConsoleOutput;
use Shim\TestSuite\TestCase;

class ConsoleOutputTest extends TestCase {

	/**
	 * @return void
	 */
	public function testOut() {
		$output = new ConsoleOutput();
		$output->write('Foo');
		$output->write('Bar');

		$result = $output->output();
		$this->assertTextContains('Foo', $result);
		$this->assertTextContains('Bar', $result);

		$array = $output->output;
		$this->assertSame('Foo' . PHP_EOL, $array[0]);
		$this->assertSame('Bar' . PHP_EOL, $array[1]);
	}

}
