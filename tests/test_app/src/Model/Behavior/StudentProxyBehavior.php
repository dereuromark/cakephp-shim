<?php
declare(strict_types=1);

namespace TestApp\Model\Behavior;

/**
 * Student variant behavior for polymorphism tests.
 */
class StudentProxyBehavior extends BaseProxyBehavior {

	/**
     * @inheritDoc
     */
	public function createLabel(string $name): string {
		return 'student:' . $name;
	}

}
