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

		$result = $entity->read('tags.2.name_not_exists');
		$this->assertNull($result);
	}

	/**
	 * @return void
	 */
	public function testReadDefaultValue() {
		$entity = new TestEntity();
		$entity->foo_bar = 'Foo Bar';

		$result = $entity->read('foo_bar', false);
		$expected = 'Foo Bar';
		$this->assertSame($expected, $result);

		$result = $entity->read('invalid', false);
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function testReadDeepDefaultValue() {
		$entity = new TestEntity();

		$entity->tags = [
			new Entity(),
			new Entity(),
			new Entity(['name' => new Entity(['subname' => 'foo'])]),
		];

		$result = $entity->read('tags.2.name.subname', false);
		$this->assertSame('foo', $result);

		$result = $entity->read('tags.2.name.subname_not_exists', false);
		$this->assertFalse($result);
	}

}
