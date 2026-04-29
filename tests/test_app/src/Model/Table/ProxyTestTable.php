<?php
declare(strict_types=1);

namespace TestApp\Model\Table;

use Cake\ORM\Table;
use Shim\Model\Table\BehaviorMethodProxyTrait;

/**
 * Test table class that uses the BehaviorMethodProxyTrait.
 */
class ProxyTestTable extends Table {

	use BehaviorMethodProxyTrait;

	/**
	 * @param array<string, mixed> $config Configuration.
	 * @return void
	 */
	public function initialize(array $config): void {
		parent::initialize($config);

		$this->setTable('posts');
	}

}
