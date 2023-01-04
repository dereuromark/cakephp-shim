<?php

namespace Shim\Test\TestCase\Model\Entity;

use RuntimeException;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntityGetTest extends TestCase {

	/**
	 * @return void
	 */
	public function testGetOrFail(): void {
		$entity = new TestEntity();
		$entity->foo_bar = 'Foo Bar';

		$result = $entity->getFooBarOrFail();
		$expected = 'Foo Bar';
		$this->assertSame($expected, $result);

		$result = $entity->getOrFail('foo_bar');
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testGetOrFailMagicInvalid(): void {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->getFooBarOrFail();
	}

	/**
	 * @return void
	 */
	public function testGetOrFailInvalid(): void {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->getOrFail('foo_bar');
	}

}
