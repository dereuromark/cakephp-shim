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
	public function testReadAssoc(): void {
		$entity = new TestEntity();

		$entity->tag = new Entity([
			'name' => 'foo',
			'country' => new Entity([
				'name' => 'country',
			]),
		]);

		$entity->require('tag.name');
		$entity->require('tag.country');
		$entity->require('tag.country.name');

		$this->expectException(RuntimeException::class);

		$entity->require('tag.country.name_not_exists');
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
		$this->expectExceptionMessage(
			'Require assertion failed for entity `' . TestEntity::class . '` and element `name_not_exists`: `tags.2.name_not_exists`',
		);

		$entity->require('tags.2.name_not_exists');
	}

	/**
	 * @return void
	 */
	public function testReadPathElementInException(): void {
		$entity = new TestEntity();

		$entity->tag = new Entity([
			'name' => 'foo',
			'state' => new Entity([
				'name' => 'state',
				'country_id' => null,
			]),
		]);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage(
			'Require assertion failed for entity `' . TestEntity::class . '` and element `country`: `tag.state.country.name_not_exists`',
		);

		$entity->require('tag.state.country.name_not_exists');
	}

}
