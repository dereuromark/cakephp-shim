# Controller shims
```
use Shim\Controller\Controller;

class AppController extends Controller {
}
```

## Paginator className
Usually a custom Paginator has to be set in each controller separately.
The Shim.Controller, if extended, allows a more global configuration.

In your config set `Paginator.className` for what Paginator should be used across all controllers:
```
'Paginator' => [
    'className' => \My\Special\CustomPaginator::class,
],
```
Your custom paginator should still extend `Cake\Datasource\Paging\PaginatorInterface`, of course.

## BC shims

### Component and Helper setup
Using the Shim Controller as base controller you can continue to use the existing setup:
```php
protected array $components = ['MyComponent'];

protected array $helpers = ['MyHelper'];
````
No deprecation notice will be thrown here then.

Note: The inheritance does not work here anymore.
So you should make sure only the final controllers contain this.
Refactor it for sure for the AppController(s) of your application.

### Cache disabling
You can also use `disableCache()` to auto-include `'Pragma' => 'no-cache'` which
shims it for older (IE) version to work there, as well.

### Response headers
Using `Configure::write('Shim.monitorHeaders')` you can monitor if all headers are properly
set via Response class and not for some reason sent prior to that.
