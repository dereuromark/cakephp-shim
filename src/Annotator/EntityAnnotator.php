<?php

namespace Shim\Annotator;

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
					if (strpos($type, '|null') !== false) {
						$type = str_replace('|null', '', $type);
					}
					if (preg_match('/^(\w+)[<\[]/', $type, $matches)) {
						$type = $matches[1];
					}

					$method = $methodType . Inflector::camelize($field) . 'OrFail(' . $type . ' $value)';
					$type = '$this';
				} else {
					$method = $methodType . Inflector::camelize($field) . 'OrFail()';
					if (strpos($type, '|null') !== false) {
						$type = str_replace('|null', '', $type);
					}
				}

				$map[$method] = new MethodAnnotation($type, $method);
			}
		}

		return $map;
	}

}
