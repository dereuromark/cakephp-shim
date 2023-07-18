<?php

use Cake\Core\Configure;

// Shims - add them in your own bootstrap/config
/*
Configure::write('Shim.monitorHeaders', true);

Configure::write('Shim.deprecationType', E_USER_ERROR);

Configure::write('Shim.deprecations.actionNames', true);

// or just (to activate all)

Configure::write('Shim.deprecations', true);
*/

Configure::write('Shim.deprecations', true);

if (!defined('SECOND')) {
	define('SECOND', 1);
}
if (!defined('MINUTE')) {
	define('MINUTE', 60);
}
if (!defined('HOUR')) {
	define('HOUR', 3600);
}
if (!defined('DAY')) {
	define('DAY', 86400);
}
if (!defined('WEEK')) {
	define('WEEK', 604800);
}
if (!defined('MONTH')) {
	define('MONTH', 2592000);
}
if (!defined('YEAR')) {
	define('YEAR', 31536000);
}
