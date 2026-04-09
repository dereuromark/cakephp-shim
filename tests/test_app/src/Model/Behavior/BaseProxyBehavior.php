<?php
declare(strict_types=1);

namespace TestApp\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * Base behavior for polymorphism tests.
 */
class BaseProxyBehavior extends Behavior {

	/**
     * Create a label - implemented differently in child behaviors.
     *
     * @param string $name Name to label.
     * @return string
     */
	public function createLabel(string $name): string {
		return 'base:' . $name;
	}

}
