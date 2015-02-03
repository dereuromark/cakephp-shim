## Features

### Upgrade helpers
Use `Configure::write('App.warnAboutNamedParams', true)` to warn about named param leftovers, that
should have been migrated to query strings. This way you can detect and fix them.

Use `Configure::write('App.warnAboutMissingContain', true)` to warn about models not being
recursive -1 and not having a contain key in the options array. Those most likely fetch
too much (or at least uncontrolled) data, which should be avoided, as 3.x won't do this either.
Better to be exact now.

Note: You can also throw exceptions (ShimException) in debug mode, if that is more useful for you, by using
`Configure::write('App.warnAboutMissingContain', 'exception')`.
In this case you should then create a custom `APP/View/Errors/shim.ctp` file as copy from an existing one, and at least
add this snippet to have some details on the query being executed:
```php
<?php if ($details = Configure::read('Exception.details')) { ?>
	<?php debug($details); ?>
<?php } ?>
```
You can also just re-use the generic `App/View/Errors/error500.ctp` and add the snippet there.

Use `Configure::write('App.deprecateField', true);` to warn about `field()` usage, which is highly
unusable with Containable and without a global recursive level of -1. So better to use this Shim plugin's
`fieldByConditions()` which will be supported in 3.x via the corresponding plugin version then.
Or switch to `find()` directly.

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


### Debugging
Use `Configure::write('App.monitorHeader', true);` to assert, that all controller actions
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
