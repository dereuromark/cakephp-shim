# Controller

Extend the Shim plugin `Controller` as your base controller to unlock the
shimmed functionality:

```php
use Shim\Controller\Controller;

class AppController extends Controller {
}
```

## Paginator className

Usually a custom paginator has to be set in each controller separately. The Shim
controller, if extended, allows a more global configuration.

In your config, set `Paginator.className` for which paginator should be used
across all controllers:

```php
'Paginator' => [
    'className' => \My\Special\CustomPaginator::class,
],
```

Your custom paginator should still extend
`Cake\Datasource\Paging\PaginatorInterface`, of course. See also the
[NumericPaginator](/datasource/numeric-paginator) shim.

## BC shims

### Component and Helper setup

Using the Shim controller as your base controller, you can continue to use the
existing setup:

```php
protected array $components = ['MyComponent'];

protected array $helpers = ['MyHelper'];
```

No deprecation notice will be thrown here.

::: warning Inheritance no longer works
The inheritance does not work here anymore. So you should make sure only the final
controllers contain this. Refactor it for the AppController(s) of your application.
:::

### Cache disabling

You can also use `disableCache()` to auto-include `'Pragma' => 'no-cache'`, which
shims it for older (IE) versions to work there as well.

### Response headers

Using `Configure::write('Shim.monitorHeaders')` you can monitor whether all
headers are properly set via the `Response` class and not, for some reason, sent
prior to that.

## Pagination conditions/contain

If you have a large app with many controllers in the old style, rewriting them can
be cumbersome. You can also use this method as an override in your AppController to
shim it for now:

```php
public function paginate(
    RepositoryInterface|QueryInterface|string|null $object = null,
    array $settings = [],
): PaginatedInterface {
    if (!is_object($object)) {
        $object = $this->fetchTable($object);
    }

    $defaults = (array)Configure::read('Paginator');
    $settings += $this->paginate + $defaults;

    /** @var class-string<\Cake\Datasource\Paging\PaginatorInterface> $paginator */
    $paginator = App::className(
        $settings['className'] ?? NumericPaginator::class,
        'Datasource/Paging',
        'Paginator',
    );
    $paginator = new $paginator();
    unset($settings['className']);

    // Shimming 4.x to 5.x
    $blacklist = [
        'conditions' => 'where',
        'contain' => 'contain',
    ];
    foreach ($blacklist as $key => $asKey) {
        if (!empty($settings[$key])) {
            if ($object instanceof RepositoryInterface) {
                $object = $object->find();
            }
            $object->$asKey($settings[$key]);
            unset($settings[$key]);
        }
    }

    try {
        $results = $paginator->paginate(
            $object,
            $this->request->getQueryParams(),
            $settings,
        );
    } catch (PageOutOfBoundsException $exception) {
        throw new NotFoundException(null, null, $exception);
    }

    return $results;
}
```

## Component base class

You can extend the Shim plugin `Component` class to have the controller available
inside by default:

```php
namespace App\Controller\Component;

use Shim\Controller\Component\Component;

/**
 * App\Controller\Component\MyComponent class
 */
class MyComponent extends Component {
}
```
