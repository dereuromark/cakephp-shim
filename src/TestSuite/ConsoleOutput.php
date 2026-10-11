<?php

namespace Shim\TestSuite;

use Cake\Console\ConsoleOutput as CakeConsoleOutput;

/**
 * Captures console writes for assertions in command tests.
 *
 * @license MIT
 * @author Mark Scherer
 */
class ConsoleOutput extends CakeConsoleOutput {

	use TestTrait;

	/**
	 * Holds all output messages.
	 *
	 * @var array<string>
	 */
	public array $output = [];

	/**
	 * Overwrite _write to output the message to debug instead of CLI.
	 *
	 * @param string $message
	 * @return int
	 */
	protected function _write(string $message): int {
		$this->debug($message);
		$this->output[] = $message;

		return 0;
	}

	/**
	 * Helper method to return the debug output as string.
	 *
	 * @return string
	 */
	public function output(): string {
		return implode('', $this->output);
	}

}
