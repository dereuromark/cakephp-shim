<?php
declare(strict_types=1);

namespace TestApp\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * Test behavior with method aliasing for BehaviorMethodProxyTrait tests.
 */
class AliasedMethodBehavior extends Behavior {

	/**
	 * @return array<string, string>
	 */
	public function implementedMethods(): array {
		return [
			'aliasedMethod' => 'actualMethod',
			'anotherAlias' => 'actualMethod',
		];
	}

	/**
	 * The actual method that gets called via aliases.
	 *
	 * @param string $value Input value.
	 * @return string
	 */
	public function actualMethod(string $value): string {
		return 'aliased:' . $value;
	}

}
