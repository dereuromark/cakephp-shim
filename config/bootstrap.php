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
