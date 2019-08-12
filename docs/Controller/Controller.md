# Controller shims

## BC shims
You can also use `disableCache()` to auto-include `'Pragma' => 'no-cache'` which
shims it for older (IE) version to work there, as well.

Using `Configure::write('Shim.monitorHeaders')` you can monitor if all headers are properly
set via Response class and not for some reason sent prior to that.

Using `Configure::write('Shim.deprecations.actionNames')` you can assert that all controller actions
have been properly migrated to the new camelBacked naming scheme. 
They must not be underscored anymore for `DashedRoute` setup.
