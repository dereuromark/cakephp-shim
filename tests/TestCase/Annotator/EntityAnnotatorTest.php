<?php

namespace Shim\Test\TestCase\Annotator;

use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\View\View;
use IdeHelper\View\Helper\DocBlockHelper;
use ReflectionClass;
use Shim\Annotator\EntityAnnotator;
use Shim\TestSuite\TestCase;
use TestApp\Model\Entity\TestEntity;

class EntityAnnotatorTest extends TestCase {

	/**
	 * @return void
	 */
	public function testBuildAnnotations(): void {
		/** @var \Shim\Annotator\EntityAnnotator $entityAnnotator */
		$entityAnnotator = $this->getMockBuilder(EntityAnnotator::class)->disableOriginalConstructor()->onlyMethods(['annotate'])->getMock();
		$entityAnnotator->setConfig('class', TestEntity::class);

		$propertyHintMap = ['id' => 'int', 'foo_bar' => '\\' . DateTime::class . '|null'];
		$helper = new DocBlockHelper(new View());
		$helper->setVirtualFields([]);

		/** @var array<\IdeHelper\Annotation\AbstractAnnotation> $result */
		$result = $this->invokeMethod($entityAnnotator, 'buildAnnotations', [$propertyHintMap, $helper]);
		$this->assertCount(6, $result);

		$this->assertSame('int $id', $result['id']->build());
		$this->assertSame('\\' . DateTime::class . '|null $foo_bar', $result['foo_bar']->build());
		$this->assertSame('int getIdOrFail()', $result['getIdOrFail()']->build());
		$this->assertSame('\\' . DateTime::class . ' getFooBarOrFail()', $result['getFooBarOrFail()']->build());
	}

	/**
	 * With `IdeHelper.genericsInParam` the iterable value type of an array/json
	 * field must survive on the setter param, so PHPStan stays clean.
	 *
	 * @return void
	 */
	public function testBuildAnnotationsKeepsSetterValueTypeWithGenericsInParam(): void {
		Configure::write('IdeHelper.genericsInParam', true);

		/** @var \Shim\Annotator\EntityAnnotator $entityAnnotator */
		$entityAnnotator = $this->getMockBuilder(EntityAnnotator::class)->disableOriginalConstructor()->onlyMethods(['annotate'])->getMock();
		$entityAnnotator->setConfig('class', TestEntity::class);

		$propertyHintMap = ['tags' => 'array<\\' . DateTime::class . '>'];
		$helper = new DocBlockHelper(new View());
		$helper->setVirtualFields([]);

		/** @var array<\IdeHelper\Annotation\AbstractAnnotation> $result */
		$result = $this->invokeMethod($entityAnnotator, 'buildAnnotations', [$propertyHintMap, $helper]);

		$key = 'setTagsOrFail(array<\\' . DateTime::class . '> $value)';
		$this->assertArrayHasKey($key, $result);
		$this->assertSame('$this ' . $key, $result[$key]->build());
	}

	/**
	 * Without the opt-in the generic collapses to the base type for backwards
	 * compatibility.
	 *
	 * @return void
	 */
	public function testBuildAnnotationsCollapsesSetterValueTypeByDefault(): void {
		Configure::delete('IdeHelper.genericsInParam');

		/** @var \Shim\Annotator\EntityAnnotator $entityAnnotator */
		$entityAnnotator = $this->getMockBuilder(EntityAnnotator::class)->disableOriginalConstructor()->onlyMethods(['annotate'])->getMock();
		$entityAnnotator->setConfig('class', TestEntity::class);

		$propertyHintMap = ['tags' => 'array<\\' . DateTime::class . '>'];
		$helper = new DocBlockHelper(new View());
		$helper->setVirtualFields([]);

		/** @var array<\IdeHelper\Annotation\AbstractAnnotation> $result */
		$result = $this->invokeMethod($entityAnnotator, 'buildAnnotations', [$propertyHintMap, $helper]);

		$this->assertArrayHasKey('setTagsOrFail(array $value)', $result);
		$this->assertSame('$this setTagsOrFail(array $value)', $result['setTagsOrFail(array $value)']->build());
	}

	/**
	 * @param object &$object Instantiated object that we will run method on.
	 * @param string $methodName Method name to call.
	 * @param array $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	protected function invokeMethod(object $object, string $methodName, array $parameters = []): mixed {
		$reflection = new ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);

		return $method->invokeArgs($object, $parameters);
	}

}
