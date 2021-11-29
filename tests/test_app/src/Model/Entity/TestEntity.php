<?php

namespace TestApp\Model\Entity;

use Cake\ORM\Entity;
use Shim\Model\Entity\GetSetTrait;
use Shim\Model\Entity\ReadTrait;

/**
 * @property string|null $foo_bar
 * @method string getFooBarOrFail()
 */
class TestEntity extends Entity {

	use GetSetTrait;
	use ReadTrait;

}
