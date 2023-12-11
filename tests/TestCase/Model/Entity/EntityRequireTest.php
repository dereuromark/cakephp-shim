<?php

namespace Shim\Test\TestCase\Model\Entity;

use RuntimeException;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntityRequireTest extends TestCase {

	/**
	 * @return void
	 */
	public function testRequire(): void {
		$entity = new TestEntity();
		$entity->foo_bar = 'Foo Bar';

		$entity->require('foo_bar');

		$result = $entity->getOrFail('foo_bar');
		$this->assertSame($entity->foo_bar, $result);
	}

	/**
	 * @return void
	 */
	public function testRequireInvalid(): void {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->require('foo_bar');
	}

}
