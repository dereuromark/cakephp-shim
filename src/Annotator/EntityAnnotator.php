<?php

namespace Shim\Annotator;

use Cake\Core\Configure;
use Cake\Utility\Inflector;
use IdeHelper\Annotation\MethodAnnotation;
use IdeHelper\Annotator\EntityAnnotator as IdeHelperEntityAnnotator;
use IdeHelper\View\Helper\DocBlockHelper;

class EntityAnnotator extends IdeHelperEntityAnnotator {

	/**
	 * @param array $propertyHintMap
	 * @param \IdeHelper\View\Helper\DocBlockHelper $helper
	 *
	 * @throws \RuntimeException
	 * @return array<\IdeHelper\Annotation\AbstractAnnotation>
	 */
	protected function buildAnnotations(array $propertyHintMap, DocBlockHelper $helper): array {
		$map = parent::buildAnnotations($propertyHintMap, $helper);

		$class = $this->getConfig('class');
		if (!$class) {
			return $map;
		}

		$methodTypes = [];
		if (method_exists($class, 'getOrFail')) {
			$methodTypes[] = 'get';
		}
		if (method_exists($class, 'setOrFail')) {
			$methodTypes[] = 'set';
		}
		if (!$methodTypes) {
			return $map;
		}

		foreach ($methodTypes as $methodType) {
			foreach ($propertyHintMap as $field => $type) {
				if ($methodType === 'set') {
					if (str_contains((string)$type, '|null')) {
						$type = str_replace('|null', '', $type);
					}
					// With `IdeHelper.genericsInParam` the property hint already carries the
					// value type (e.g. `array<\App\Model\Entity\User>`); keep it so the setter
					// param stays PHPStan-clean (no `missingType.iterableValue`). Without the
					// opt-in, collapse generics to the base type for backwards compatibility.
					if (!Configure::read('IdeHelper.genericsInParam') && preg_match('/^(\w+)[<\[]/', (string)$type, $matches)) {
						$type = $matches[1];
					}

					$method = $methodType . Inflector::camelize($field) . 'OrFail(' . $type . ' $value)';
					$type = '$this';
				} else {
					$method = $methodType . Inflector::camelize($field) . 'OrFail()';
					if (str_contains((string)$type, '|null')) {
						$type = str_replace('|null', '', $type);
					}
				}

				$map[$method] = new MethodAnnotation($type, $method);
			}
		}

		return $map;
	}

}
