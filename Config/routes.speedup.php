<?php
/**
 * This can highly speed up the 2.x routing of your application.
 * Only use it for CakePHP2.6+, though!
 * The conditional check can then be safely removed :)
 *
 * To use, rename your routes.php file to routes.connect.php and copy the following code to the
 * routes.php file instead.
 */

$routesFile = __DIR__ . DS . 'routes.connect.php';

if ((float)Configure::version() >= 2.6) {
	$routesHash = sha1_file($routesFile);

	$cacheFile = TMP . 'routes-' . $routesHash . '.php';
	if (file_exists($cacheFile)) {
		App::uses('PluginShortRoute', 'Routing/Route');
		include $cacheFile;
	} else {
		include $routesFile;

		// Prepare for cache
		foreach (Router::$routes as $i => $route) {
			$route->compile();
		}

		$tmpCacheFile = TMP . 'routes-' . uniqid('tmp-', true) . '.php';
		file_put_contents($tmpCacheFile, '<?php
			Router::$initialized = true;
			Router::$routes = ' . var_export(Router::$routes, true) . ';
		');
		rename($tmpCacheFile, $cacheFile);
	}
} else {
	include $routesFile;
}
