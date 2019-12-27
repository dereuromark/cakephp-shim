<?php
/**
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT', dirname(__DIR__));
define('APP_DIR', 'src');

define('APP', rtrim(sys_get_temp_dir(), DS) . DS . APP_DIR . DS);
if (!is_dir(APP)) {
	mkdir(APP, 0770, true);
}

define('TMP', ROOT . DS . 'tmp' . DS);
if (!is_dir(TMP)) {
	mkdir(TMP, 0770, true);
}
define('CONFIG', ROOT . DS . 'tests' . DS . 'config' . DS);

define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);

define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . APP_DIR . DS);

require dirname(__DIR__) . '/vendor/autoload.php';
require CORE_PATH . 'config/bootstrap.php';

Cake\Core\Configure::write('App', [
	'encoding' => 'UTF-8',
	'namespace' => 'App',
	'paths' => [
		'templates' => [ROOT . DS . 'tests' . DS . 'test_app' . DS . 'templates' . DS],
	],
	'fullBaseUrl' => 'http://localhost',
]);

Cake\Core\Configure::write('debug', true);

require ROOT . DS . 'config/bootstrap.php';

$cache = [
	'default' => [
		'engine' => 'File',
	],
	'_cake_core_' => [
		'className' => 'File',
		'prefix' => 'crud_myapp_cake_core_',
		'path' => CACHE . 'persistent/',
		'serialize' => true,
		'duration' => '+10 seconds',
	],
	'_cake_model_' => [
		'className' => 'File',
		'prefix' => 'crud_my_app_cake_model_',
		'path' => CACHE . 'models/',
		'serialize' => 'File',
		'duration' => '+10 seconds',
	],
];

Cake\Cache\Cache::setConfig($cache);

//needed?
Cake\Core\Plugin::getCollection()->add(new Shim\Plugin());

// Ensure default test connection is defined
if (getenv('DB_DSN')) {
	Cake\Datasource\ConnectionManager::setConfig('test', [
		'className' => 'Cake\Database\Connection',
		'url' => getenv('DB_DSN'),
		'timezone' => 'UTC',
		'quoteIdentifiers' => true,
		'cacheMetadata' => true,
	]);

	return;
}

if (!getenv('DB_CLASS')) {
	putenv('DB_CLASS=Cake\Database\Driver\Sqlite');
	putenv('DB_DSN=sqlite:///:memory:');
}

Cake\Datasource\ConnectionManager::setConfig('test', [
	'className' => 'Cake\Database\Connection',
	'driver' => getenv('DB_CLASS'),
	'dsn' => getenv('DB_DSN'),
	'timezone' => 'UTC',
	'quoteIdentifiers' => true,
	'cacheMetadata' => true,
]);
