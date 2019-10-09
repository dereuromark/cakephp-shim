# Controller shims

## BC shims

### Cache disabling
You can also use `disableCache()` to auto-include `'Pragma' => 'no-cache'` which
shims it for older (IE) version to work there, as well.

### Response headers
Using `Configure::write('Shim.monitorHeaders')` you can monitor if all headers are properly
set via Response class and not for some reason sent prior to that.

### Controller action naming
Using `Configure::write('Shim.deprecations.actionNames')` you can assert that all controller actions
have been properly migrated to the new camelBacked naming scheme. 
They must not be underscored anymore for `DashedRoute` setup.

Note: This shim will be removed in 4.x

### `uses` vs `modelClass`
The plugin shims controller `$uses` with a deprecation warning.
The first element will only be used and set to `$modelClass`.
If you defined multiple models, you need to refactor.

Note: This shim will be removed in 4.x, make sure you use only `$modelClass` property ASAP.
