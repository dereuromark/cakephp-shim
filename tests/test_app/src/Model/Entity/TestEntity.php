<?php

namespace TestApp\Model\Entity;

use Cake\ORM\Entity;
use Shim\Model\Entity\GetTrait;
use Shim\Model\Entity\ReadTrait;

/**
 * @property string|null $foo_bar
 * @method string getFooBarOrFail()
 */
class TestEntity extends Entity {

	use GetTrait;
	use ReadTrait;

}
