<?php

namespace Shim\Test\TestCase\Model\Entity;

use Cake\ORM\Entity;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntityReadTest extends TestCase {

	/**
	 * @return void
	 */
	public function testRead() {
		$entity = new TestEntity();
		$entity->foo_bar = 'Foo Bar';

		$result = $entity->read('foo_bar');
		$expected = 'Foo Bar';
		$this->assertSame($expected, $result);

		$result = $entity->read('invalid');
		$this->assertNull($result);
	}

	/**
	 * @return void
	 */
	public function testReadDeep() {
		$entity = new TestEntity();

		$entity->tags = [
			new Entity(),
			new Entity(),
			new Entity(['name' => 'foo']),
		];

		$result = $entity->read('tags.2.name');

		$this->assertSame('foo', $result);
	}

}
