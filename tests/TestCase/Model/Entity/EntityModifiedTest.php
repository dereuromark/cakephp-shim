<?php

namespace Shim\Test\TestCase\Model\Entity;

use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntityModifiedTest extends TestCase {

	/**
	 * @return void
	 */
	public function testGetModifiedFields(): void {
		$entity = new TestEntity(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'], ['markClean' => true, 'markNew' => false]);

		$entity->set('foo', 'foo');
		$entity->set('bar', 'baaaaaar');
		$entity->set('foo_bar', 'foo bar');

		$result = $entity->getDirty();
		$expected = ['foo', 'bar', 'foo_bar'];
		$this->assertEquals($expected, $result);

		$result = $entity->isDirty('foo');
		$this->assertTrue($result);
		$result = $entity->isModified('foo');
		$this->assertFalse($result);

		$result = $entity->getModifiedFields();
		$expected = ['bar', 'foo_bar'];
		$this->assertEquals($expected, $result);
	}

}
