<?php

namespace TestApp\Model\Entity;

use Cake\ORM\Entity;
use Shim\Model\Entity\GetTrait;

/**
 * @property string|null $foo_bar
 * @method string getFooBarOrFail()
 */
class TestEntity extends Entity {

	use GetTrait;

}
