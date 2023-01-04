<?php

namespace Shim\Generator\Task;

use IdeHelper\Generator\Directive\ExpectedArguments;
use IdeHelper\Generator\Directive\RegisterArgumentsSet;
use IdeHelper\Generator\Task\ConfigureTask;
use Shim\View\Helper\ConfigureHelper;

class ConfigureHelperTask extends ConfigureTask {

	public const CLASS_CONFIGURE = ConfigureHelper::class;

	/**
	 * @var string
	 */
	public const SET_CONFIGURE_KEYS = 'configureHelperKeys';

	/**
	 * @var array<int>
	 */
	protected array $methods = [
		'\\' . self::CLASS_CONFIGURE . '::read()' => 0,
		'\\' . self::CLASS_CONFIGURE . '::readOrFail()' => 0,
		'\\' . self::CLASS_CONFIGURE . '::check()' => 0,
		'\\' . self::CLASS_CONFIGURE . '::consume()' => 0,
		'\\' . self::CLASS_CONFIGURE . '::consumeOrFail()' => 0,
	];

	/**
	 * @return array<\IdeHelper\Generator\Directive\BaseDirective>
	 */
	public function collect(): array {
		$result = [];

		$list = $this->collectKeys();
		$registerArgumentsSet = new RegisterArgumentsSet(static::SET_CONFIGURE_KEYS, $list);
		$result[$registerArgumentsSet->key()] = $registerArgumentsSet;

		foreach ($this->methods as $method => $position) {
			$directive = new ExpectedArguments($method, $position, [$registerArgumentsSet]);
			$result[$directive->key()] = $directive;
		}

		return $result;
	}

}
