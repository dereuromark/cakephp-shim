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
	 * @return \IdeHelper\Annotation\AbstractAnnotation[]
	 * @throws \RuntimeException
	 */
	protected function buildAnnotations(array $propertyHintMap, DocBlockHelper $helper): array {
		$map = parent::buildAnnotations($propertyHintMap, $helper);

		$class = $this->getConfig('class');
		if (!$class || !method_exists($class, 'getOrFail')) {
			return $map;
		}

		foreach ($propertyHintMap as $field => $type) {
			$method = 'get' . Inflector::camelize($field) . 'OrFail()';
			if (strpos($type, '|null') !== false) {
				$type = str_replace('|null', '', $type);
			}

			$map[] = new MethodAnnotation($type, $method);
		}

		return $map;
	}

}
