<?php

namespace Shim\Test\TestCase\Command;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Shim\Command\Command;

class CommandTest extends TestCase {

	/**
	 * @return void
	 */
	public function testCommand(): void {
		$command = new Command();

		$io = new ConsoleIo();
		$this->assertNull($command->run([], $io));
	}

}
