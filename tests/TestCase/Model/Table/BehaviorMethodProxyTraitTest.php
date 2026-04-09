<?php
declare(strict_types=1);

namespace Shim\Test\TestCase\Model\Table;

use BadMethodCallException;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Shim\TestSuite\TestCase;
use TestApp\Model\Table\ProxyTestTable;

class BehaviorMethodProxyTraitTest extends TestCase {

	protected ProxyTestTable $Table;

	/**
	 * @var array<string>
	 */
	protected array $fixtures = [
		'plugin.Shim.Posts',
	];

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('App.namespace', 'TestApp');

		$this->Table = new ProxyTestTable();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		TableRegistry::getTableLocator()->clear();

		parent::tearDown();
	}

	/**
	 * Test that behavior methods can be called on the table.
	 *
	 * @return void
	 */
	public function testProxyMethodCall(): void {
		$this->Table->addBehavior('ProxyTest');

		$result = $this->Table->proxyableMethod('test');

		$this->assertSame('proxied:test', $result);
	}

	/**
	 * Test that methods with multiple arguments work.
	 *
	 * @return void
	 */
	public function testProxyMethodWithMultipleArgs(): void {
		$this->Table->addBehavior('ProxyTest');

		$result = $this->Table->multiArgMethod('foo', 42, true);

		$expected = ['a' => 'foo', 'b' => 42, 'c' => true];
		$this->assertSame($expected, $result);
	}

	/**
	 * Test that method calls are cached for performance.
	 *
	 * @return void
	 */
	public function testMethodCaching(): void {
		$this->Table->addBehavior('ProxyTest');

		// First call - populates cache
		$result1 = $this->Table->proxyableMethod('first');
		$this->assertSame('proxied:first', $result1);

		// Second call - uses cache
		$result2 = $this->Table->proxyableMethod('second');
		$this->assertSame('proxied:second', $result2);
	}

	/**
	 * Test that cache can be cleared.
	 *
	 * @return void
	 */
	public function testClearCache(): void {
		$this->Table->addBehavior('ProxyTest');

		$this->Table->proxyableMethod('test');
		$this->Table->clearBehaviorMethodCache();

		// Should still work after cache clear
		$result = $this->Table->proxyableMethod('test');
		$this->assertSame('proxied:test', $result);
	}

	/**
	 * Test polymorphic dispatch - teacher variant.
	 *
	 * @return void
	 */
	public function testPolymorphicDispatchTeacher(): void {
		$this->Table->addBehavior('TeacherProxy');

		$result = $this->Table->createLabel('John');

		$this->assertSame('teacher:John', $result);
	}

	/**
	 * Test polymorphic dispatch - student variant.
	 *
	 * @return void
	 */
	public function testPolymorphicDispatchStudent(): void {
		$this->Table->addBehavior('StudentProxy');

		$result = $this->Table->createLabel('Jane');

		$this->assertSame('student:Jane', $result);
	}

	/**
	 * Test switching behaviors at runtime.
	 *
	 * @return void
	 */
	public function testSwitchingBehaviors(): void {
		// Start with teacher behavior
		$this->Table->addBehavior('TeacherProxy');
		$result = $this->Table->createLabel('Person');
		$this->assertSame('teacher:Person', $result);

		// Switch to student behavior
		$this->Table->removeBehavior('TeacherProxy');
		$this->Table->clearBehaviorMethodCache();
		$this->Table->addBehavior('StudentProxy');

		$result = $this->Table->createLabel('Person');
		$this->assertSame('student:Person', $result);
	}

	/**
	 * Test that unknown methods throw exception.
	 *
	 * @return void
	 */
	public function testUnknownMethodThrowsException(): void {
		$this->Table->addBehavior('ProxyTest');

		$this->expectException(BadMethodCallException::class);
		$this->expectExceptionMessage('Unknown method `nonExistentMethod`');

		$this->Table->nonExistentMethod();
	}

	/**
	 * Test that static methods ARE proxied (matching original CakePHP behavior).
	 *
	 * Note: CakePHP's implementedMethods() includes static methods,
	 * so they are proxied just like instance methods.
	 *
	 * @return void
	 */
	public function testStaticMethodsAreProxied(): void {
		$this->Table->addBehavior('ProxyTest');

		// Static methods are included in implementedMethods() by CakePHP
		// and can be called as instance methods (PHP allows this)
		$result = $this->Table->staticMethod();

		$this->assertSame('static', $result);
	}

	/**
	 * Test that protected methods are not proxied.
	 *
	 * @return void
	 */
	public function testProtectedMethodsNotProxied(): void {
		$this->Table->addBehavior('ProxyTest');

		$this->expectException(BadMethodCallException::class);

		$this->Table->protectedMethod();
	}

	/**
	 * Test that dynamic finders still work.
	 *
	 * @return void
	 */
	public function testDynamicFindersStillWork(): void {
		$this->Table->addBehavior('ProxyTest');

		$result = $this->Table->findById(1);

		$this->assertNotEmpty($result->toArray());
	}

	/**
	 * Test case insensitivity for method names.
	 *
	 * @return void
	 */
	public function testMethodNameCaseInsensitivity(): void {
		$this->Table->addBehavior('ProxyTest');

		// Call with different case - should use cache from first call
		$result1 = $this->Table->proxyableMethod('test');
		$result2 = $this->Table->ProxyableMethod('test');

		$this->assertSame($result1, $result2);
	}

	/**
	 * Test that method aliasing via implementedMethods() works.
	 *
	 * @return void
	 */
	public function testMethodAliasing(): void {
		$this->Table->addBehavior('AliasedMethod');

		// Call via alias - should invoke actualMethod()
		$result = $this->Table->aliasedMethod('test');

		$this->assertSame('aliased:test', $result);
	}

	/**
	 * Test that multiple aliases to the same method work.
	 *
	 * @return void
	 */
	public function testMultipleAliasesToSameMethod(): void {
		$this->Table->addBehavior('AliasedMethod');

		$result1 = $this->Table->aliasedMethod('one');
		$result2 = $this->Table->anotherAlias('two');

		$this->assertSame('aliased:one', $result1);
		$this->assertSame('aliased:two', $result2);
	}

}
