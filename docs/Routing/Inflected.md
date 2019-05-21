# Route shim

## InflectedRoute
InflectedRoute exists as 1:1 replacement when upgrading from 2.x.
The core one still expects method names for actions as `foo_bar()` underscored, which does not make sense (not only because of PSR).
This `Shim.InflectedRoute` class will work with the same method naming scheme as all other routes in 3.x: `fooBar()` camelBacked actions as method names.

So in your `routes.php` class:
```php
// At the top of the file
Router::defaultRouteClass('Shim.InflectedRoute');

// Also any other mentioned class name
..., ['routeClass' => 'Shim.InflectedRoute']
```

`/plugin_name/controller_name/action_name` now maps to PluginName plugin and `ControllerName::actionName()`.
The array to form such a URL is like with Dashed routing: `['plugin' => 'PluginName', 'controller' => 'ControllerName', 'action' => 'actionName']`
