<?php

namespace Shim\Test\TestCase\Model\Entity;

use Cake\ORM\Entity;
use RuntimeException;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntityRequireTest extends TestCase {

	/**
	 * @doesNotPerformAssertions
	 * @return void
	 */
	public function testRead(): void {
		$entity = new TestEntity();
		$entity->foo_bar = 'Foo Bar';

		$entity->require('foo_bar');
	}

	/**
	 * @return void
	 */
	public function testReadDeep(): void {
		$entity = new TestEntity();

		$entity->tags = [
			new Entity(),
			new Entity(),
			new Entity(['name' => 'foo']),
		];

		$entity->require('tags.2.name');

		$this->expectException(RuntimeException::class);

		$entity->require('tags.2.name_not_exists');
	}

}
