<?php

namespace Shim\Command;

use Cake\Command\Command as CakeCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

/**
 * Convenience wrapper to more easily access args and io in submethods.
 *
 * Your execute() must call `parent::execute($args, $io);` as first line.
 */
class Command extends CakeCommand {

	/**
	 * @var \Cake\Console\Arguments
	 */
	protected $args;

	/**
	 * @var \Cake\Console\ConsoleIo
	 */
	protected $io;

	/**
	 * @param \Cake\Console\Arguments $args
	 * @param \Cake\Console\ConsoleIo $io
	 *
	 * @return int|null|void
	 */
	public function execute(Arguments $args, ConsoleIo $io) {
		$this->args = $args;
		$this->io = $io;
	}

}
