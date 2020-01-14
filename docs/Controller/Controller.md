# Controller shims

## BC shims

### Component and Helper setup
Using the Shim Controller as base controller you can continue to use the existing setup:
```php
protected $components = ['MyComponent'];

protected $helpers = ['MyHelper'];
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
