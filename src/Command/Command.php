<?php

namespace Shim\Command;

use Cake\Command\Command as CakeCommand;

/**
 * Compatibility class for commands using inherited args and io properties.
 *
 * CakePHP 6 initializes args and io before calling execute().
 */
class Command extends CakeCommand {
}
