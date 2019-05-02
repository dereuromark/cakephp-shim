<?php

namespace Shim\Test\TestCase\Model\Entity;

use RuntimeException;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntityGetTest extends TestCase {

	/**
	 * @return void
	 */
	public function testGet() {
		$entity = new TestEntity();
		$entity->foo_bar = 'Foo Bar';

		$result = $entity->getFooBarOrFail();
		$expected = 'Foo Bar';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testGetFail() {
		$entity = new TestEntity();

		$this->expectException(RuntimeException::class);

		$entity->getFooBarOrFail();
	}

}
