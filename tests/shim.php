<?php

use Cake\Controller\Controller;

error_reporting(E_ALL & ~E_USER_DEPRECATED);

class_alias(Controller::class, 'App\Controller\AppController');
