## Features

### Upgrade helpers
Use `Configure::write('Shim.warnAboutNamedParams', true)` to warn about named param leftovers, that
should have been migrated to query strings. This way you can detect and fix them.
After you finished migrating them, you can use `Configure::write('Shim.handleNamedParams', true)` to 301-redirect all
to the corresponding new query string URLs. Note that in debug mode, it will be a 302 redirect as debugging would be more difficult otherwise.
If you rather throw a 404 exception, use `Configure::write('Shim.handleNamedParams', 'exception')`.

Use `Configure::write('Shim.warnAboutMissingContain', true)` to warn about models not being
recursive -1 and not having a contain key in the options array. Those most likely fetch
too much (or at least uncontrolled) data, which should be avoided, as 3.x won't do this either.
Better to be exact now.

Note: You can also throw exceptions (ShimException) in debug mode, if that is more useful for you, by using
`Configure::write('Shim.warnAboutMissingContain', 'exception')`.
In this case you should then create a custom `APP/View/Errors/shim.ctp` file as copy from an existing one, and at least
add this snippet to have some details on the query being executed:
```php
<?php if ($details = Configure::read('Exception.details')) { ?>
	<?php debug($details); ?>
<?php } ?>
```
You can also just re-use the generic `App/View/Errors/error500.ctp` and add the snippet there.

Use `Configure::write('Shim.deprecateField', true);` to warn about `field()` usage, which is highly
unusable with Containable and without a global recursive level of -1. So better to use this Shim plugin's
`fieldByConditions()` which will be supported in 3.x via the corresponding plugin version then.
Or switch to `find()` directly.

use `Configure::write('Shim.deprecateSaveField', true);` to warn about `saveField()` usage which will also be
not available in future versions and as such should be replaced by `save()` or `updateAll()` instead.

Use the FormShim helper to find very difficult to detect issues around Form::end():
```php
$this->Form->end('Some string');
// should be
$this->Form->submit('Some string');
$this->Form->end();
```
In 3.x that will simply not output anything anymore (~~silent error so to speak~~ fixed in [5810](https://github.com/cakephp/cakephp/pull/5810)).
As such, you need to go away from that as soon as possible.
By extending that helper you make sure you catch all of those in time:
```php
//public $helpers = array('Form');
// use this instead:
public $helpers = array('Form' => array('className' => 'Shim.FormShim'));
```

You can also use the FormShim and HtmlShim helpers to detect leftovers of deprecated `$confirmMessage` usage in link() and postLink().

### Seo
A big problem in 2.x is the [URL casing ambiguity](https://github.com/cakephp/cakephp/issues/2125).
This can create duplicate content issues with Google and other search engines, as they might get a link
to the same page in different variations:
- /controllerName/action_name
- /ControllerName/ActionName
- /controller_name/action_name (correct as per conventions)
All three URLs route it just fine, which shouldn't happen.

Use the SeoDispatcher filter to handle this.
First, enable it in your bootstrap:
```php
Configure::write('Dispatcher.filters', array(
	...
	'Shim.SeoDispatcher'
));
```
By default it will 301-redirect to the correct URL (in debug mode 302 to avoid debugging issues).
If you want to temporarilly disable the redirect in debug mode, you can append `?skip_seo=1` to the URL.

If you want to 404 instead, use `Configure::write('Shim.handleSeo', 'exception')`.

Note: this filter uses 3-4 Inflector calls per request. This is pretty much nothing (in comparison to the
whole dispatching process), so the overhead is minimal compared to the benefit it brings.
Don't worry about using this in productive and heavy-traffic sites.

Also note: It expects you to follow the conventions: Consistent "snake_case" usage for URLs and for actions.

### Debugging
Use `Configure::write('Shim.monitorHeader', true);` to assert, that all controller actions
don't (accidentally) sent any headers prior to the actual response->send() call.
It will throw an exception (ShimException) in debug mode, and trigger an error in productive mode.

Make sure your AppController extends the Shim plugin ShimController:
```php
App::uses('ShimController', 'Shim.Controller');

/**
 * Your AppController class
 */
class AppController extends ShimController {
```

By default it is not active, and when activated via bootstrap you can always temporally or
locally deactivate it for specific controllers/actions.
