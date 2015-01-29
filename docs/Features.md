## Features

### Upgrade helpers
Use `Configure::write('App.warnAboutNamedParams', true)` to warn about named param leftovers, that
should have been migrated to query strins. This way you can detect and fix them.

Use `Configure::write('App.warnAboutMissingContain', true)` to warn about models not being
recursive -1 and not havin a contain key in the options array. Those most likely fetch
too much (or at least uncontrolled) data, which should be avoided, as 3.x won't do this either.
Better to be exact now.


### Debugging
Use `Configure::write('App.monitorHeader', true);` to assert, that all controller actions
don't (accidently) sent any headers prior to the actual response->send() call.
It will throw an exception in debug mode, and trigger an error in productive mode.

Make sure your AppController extends the Shim plugin ShimController:
```php
App::uses('ShimController', 'Shim.Controller');

/**
 * Your AppController class
 */
class AppController extends ShimController {
```

By default it is not active, and when activated via bootstrap you can always temporarally or
locally deactivate it for specific controllers/actions.

