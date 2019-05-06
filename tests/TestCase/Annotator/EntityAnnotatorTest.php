<?php

namespace Shim\Test\TestCase\Annotator;

use Cake\I18n\FrozenTime;
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
	public function testBuildAnnotations() {
		/** @var \Shim\Annotator\EntityAnnotator $entityAnnotator */
		$entityAnnotator = $this->getMockBuilder(EntityAnnotator::class)->disableOriginalConstructor()->setMethods(['annotate'])->getMock();
		$entityAnnotator->setConfig('class', TestEntity::class);

		$propertyHintMap = ['id' => 'int', 'foo_bar' => '\\' . FrozenTime::class . '|null'];
		$helper = new DocBlockHelper(new View());

		/** @var \IdeHelper\Annotation\AbstractAnnotation[] $result */
		$result = $this->invokeMethod($entityAnnotator, 'buildAnnotations', [$propertyHintMap, $helper]);
		$this->assertCount(4, $result);

		$this->assertSame('int $id', $result[0]->build());
		$this->assertSame('\\' . FrozenTime::class . '|null $foo_bar', $result[1]->build());
		$this->assertSame('int getIdOrFail()', $result[2]->build());
		$this->assertSame('\\' . FrozenTime::class . ' getFooBarOrFail()', $result[3]->build());
	}

	/**
	 * @param object &$object Instantiated object that we will run method on.
	 * @param string $methodName Method name to call.
	 * @param array $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	protected function invokeMethod(&$object, $methodName, array $parameters = []) {
		$reflection = new ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
	}

}
