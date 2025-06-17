<?php

namespace Shim\Annotator\ClassAnnotatorTask;

use IdeHelper\Annotator\ClassAnnotatorTask\ModelAwareClassAnnotatorTask;
use ReflectionClass;
use Throwable;

/**
 * Classes that use ModelAwareTrait should automatically have used tables - via loadModel() call - annotated.
 */
class LegacyModelAwareClassAnnotatorTask extends ModelAwareClassAnnotatorTask {

	/**
	 * Deprecated: $content, use $this->content instead.
	 *
	 * @param string $path
	 * @param string $content
	 * @return bool
	 */
	public function shouldRun(string $path, string $content): bool {
		if (!str_contains($path, DS . 'src' . DS)) {
			return false;
		}
		if (preg_match('#\buse ModelAwareTrait\b#', $content)) {
			return true;
		}

		/** @var class-string|null $className */
		$className = $this->getClassName($path, $content);
		if (!$className) {
			return false;
		}

		try {
			return (new ReflectionClass($className))->hasMethod('loadModel');
		} catch (Throwable $exception) {
			return false;
		}
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	public function annotate(string $path): bool {
		$models = $this->getUsedModels($this->content);

		$annotations = $this->getModelAnnotations($models, $this->content);

		return $this->annotateContent($path, $this->content, $annotations);
	}

	/**
	 * @param string $content
	 *
	 * @return array<string>
	 */
	protected function getUsedModels(string $content): array {
		preg_match_all('/\$this-\>loadModel\(\'([a-z.]+)\'/i', $content, $matches);
		if (empty($matches[1])) {
			return [];
		}

		$models = $matches[1];

		return array_unique($models);
	}

	/**
	 * @param string $path
	 * @param string $content
	 *
	 * @return string|null
	 */
	protected function getClassName(string $path, string $content): ?string {
		preg_match('#^namespace (.+)\b#m', $content, $matches);
		if (!$matches) {
			return null;
		}

		$className = pathinfo($path, PATHINFO_FILENAME);

		return $matches[1] . '\\' . $className;
	}

}
