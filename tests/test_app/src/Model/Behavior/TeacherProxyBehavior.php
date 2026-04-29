<?php
declare(strict_types=1);

namespace TestApp\Model\Behavior;

/**
 * Teacher variant behavior for polymorphism tests.
 */
class TeacherProxyBehavior extends BaseProxyBehavior {

	/**
     * @inheritDoc
     */
	public function createLabel(string $name): string {
		return 'teacher:' . $name;
	}

}
