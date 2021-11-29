<?php

namespace Shim\Test\TestCase\Model\Entity;

use RuntimeException;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntitySetTest extends TestCase {

	/**
	 * @return void
	 */
	public function testSetOrFail() {
		$entity = new TestEntity();
		$entity->setOrFail('foo_bar', 'Foo Bar');

		$result = $entity->getFooBarOrFail();
		$expected = 'Foo Bar';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testSetOrFailMagicInvalid() {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->setFooBarOrFail('foo_bar', null);
	}

	/**
	 * @return void
	 */
	public function testGetOrFailInvalid() {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->setOrFail('foo_bar', null);
	}

}
