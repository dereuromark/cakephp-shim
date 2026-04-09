<?php
declare(strict_types=1);

namespace Shim\Model\Table;

use BadMethodCallException;
use Cake\ORM\Behavior;
use ReflectionException;
use ReflectionMethod;

/**
 * Restores the pre-5.3 behavior of proxying method calls to behaviors.
 *
 * In CakePHP 5.3, calling behavior methods directly on the table instance
 * was deprecated. This trait restores that functionality for applications
 * that rely on polymorphic behavior dispatch.
 *
 * Usage:
 * ```php
 * class AppTable extends Table
 * {
 *     use BehaviorMethodProxyTrait;
 * }
 * ```
 *
 * This allows calling `$table->behaviorMethod()` instead of
 * `$table->getBehavior('BehaviorName')->behaviorMethod()`.
 */
trait BehaviorMethodProxyTrait {

	/**
     * Cache of behavior method mappings.
     *
     * Structure: ['methodname' => ['BehaviorName', 'actualMethodName']]
     *
     * @var array<string, array{0: string, 1: string}>
     */
	protected array $_behaviorMethodCache = [];

	/**
     * Proxies method calls to behaviors without deprecation warnings.
     *
     * @param string $method Method name.
     * @param array $args Arguments.
     * @throws \BadMethodCallException When method is not found.
     * @return mixed
     */
	public function __call(string $method, array $args): mixed {
		$lowercaseMethod = strtolower($method);

		// Check cache first
		if (isset($this->_behaviorMethodCache[$lowercaseMethod])) {
			[$behaviorName, $actualMethod] = $this->_behaviorMethodCache[$lowercaseMethod];
			$behavior = $this->behaviors()->get($behaviorName);
			if ($behavior) {
				return $behavior->{$actualMethod}(...$args);
			}
			// Behavior was unloaded, clear cache entry
			unset($this->_behaviorMethodCache[$lowercaseMethod]);
		}

		// Search all loaded behaviors for the method
		$behaviors = $this->behaviors();
		foreach ($behaviors->loaded() as $name) {
			$behavior = $behaviors->get($name);
			if (!$behavior) {
				continue;
			}

			$callable = $this->_findBehaviorMethod($behavior, $method);
			if ($callable !== null) {
				[$actualMethod] = $callable;
				// Cache for future calls
				$this->_behaviorMethodCache[$lowercaseMethod] = [$name, $actualMethod];

				return $behavior->{$actualMethod}(...$args);
			}
		}

		// Fall back to dynamic finders (findBy*, findAllBy*)
		if (preg_match('/^find(?:\w+)?By/', $method) > 0) {
			return $this->_dynamicFinder($method, $args);
		}

		throw new BadMethodCallException(
			sprintf('Unknown method `%s` called on `%s`', $method, static::class),
		);
	}

	/**
     * Find a callable method on a behavior.
     *
     * Returns null if the method doesn't exist or is not callable.
     * Excludes finder methods (those are handled separately by the ORM).
     *
     * @param \Cake\ORM\Behavior $behavior The behavior to check.
     * @param string $method The method name to find.
     * @return array{0: string}|null Array with actual method name, or null if not found.
     */
	protected function _findBehaviorMethod(Behavior $behavior, string $method): ?array {
		// Skip finder methods - those are handled by BehaviorRegistry::callFinder()
		if (str_starts_with(strtolower($method), 'find')) {
			return null;
		}

		if (!method_exists($behavior, $method)) {
			return null;
		}

		try {
			$reflection = new ReflectionMethod($behavior, $method);
		} catch (ReflectionException) {
			return null;
		}

		// Must be public and not static
		if (!$reflection->isPublic() || $reflection->isStatic()) {
			return null;
		}

		// Skip methods defined on the base Behavior class
		$declaringClass = $reflection->getDeclaringClass()->getName();
		if ($declaringClass === Behavior::class) {
			return null;
		}

		return [$method];
	}

	/**
     * Clear the behavior method cache.
     *
     * Call this if you dynamically add/remove behaviors and want to
     * ensure the cache is fresh.
     *
     * @return void
     */
	public function clearBehaviorMethodCache(): void {
		$this->_behaviorMethodCache = [];
	}

}
