<?php
declare(strict_types=1);

namespace TestApp\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * Test behavior for BehaviorMethodProxyTrait tests.
 */
class ProxyTestBehavior extends Behavior {

	/**
     * A simple method that can be proxied.
     *
     * @param string $value Input value.
     * @return string
     */
	public function proxyableMethod(string $value): string {
		return 'proxied:' . $value;
	}

	/**
     * Method with multiple arguments.
     *
     * @param string $a First arg.
     * @param int $b Second arg.
     * @param bool $c Third arg.
     * @return array<string, mixed>
     */
	public function multiArgMethod(string $a, int $b, bool $c = false): array {
		return ['a' => $a, 'b' => $b, 'c' => $c];
	}

	/**
     * Static methods should NOT be proxied.
     *
     * @return string
     */
	public static function staticMethod(): string {
		return 'static';
	}

	/**
     * Protected methods should NOT be proxied.
     *
     * @return string
     */
	protected function protectedMethod(): string {
		return 'protected';
	}

}
