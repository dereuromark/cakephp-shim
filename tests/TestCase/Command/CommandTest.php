<?php

namespace Shim\Test\TestCase\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\TestSuite\TestCase;
use Shim\Command\Command;

class CommandTest extends TestCase {

	/**
	 * @return void
	 */
	public function testCommand(): void {

		$command = new Command();

		$args = new Arguments([], [], []);
		$io = new ConsoleIo();
		$command->execute($args, $io);

		$this->assertTrue(true);
	}

}
