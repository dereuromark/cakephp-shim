<?php
use Cake\Core\Configure;

// Shims - add them in your own bootstrap/config
/*
Configure::write('Shim.monitorHeaders', true);
Configure::write('Shim.assertActionNames', true);

Configure::write('Shim.deprecations.newEntity', true);
Configure::write('Shim.deprecations.urlBuild', true);
// or just (to activate all)
Configure::write('Shim.deprecations', true);
*/

Configure::write('Shim.deprecations', true);
