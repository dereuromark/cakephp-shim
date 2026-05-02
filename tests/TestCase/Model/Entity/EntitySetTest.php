<?php

namespace Shim\Test\TestCase\Model\Entity;

use RuntimeException;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntitySetTest extends TestCase {

	/**
	 * @return void
	 */
	public function testSetOrFail(): void {
		$entity = new TestEntity();
		$entity->setOrFail('foo_bar', 'Foo Bar');

		$result = $entity->getFooBarOrFail();
		$expected = 'Foo Bar';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testSetOrFailMagic(): void {
		$entity = new TestEntity();
		$entity->setFooBarOrFail('Foo Bar');

		$this->assertSame('Foo Bar', $entity->foo_bar);
		$this->assertSame('Foo Bar', $entity->getFooBarOrFail());
	}

	/**
	 * @return void
	 */
	public function testSetOrFailMagicInvalid(): void {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->setFooBarOrFail(null);
	}

	/**
	 * @return void
	 */
	public function testSetOrFailMagicMissingArgument(): void {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('param for value not found');

		/** @phpstan-ignore-next-line */
		$entity->setFooBarOrFail();
	}

	/**
	 * @return void
	 */
	public function testGetOrFailInvalid(): void {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->setOrFail('foo_bar', null);
	}

}
