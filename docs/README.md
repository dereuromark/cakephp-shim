## Shim plugin Documentation

### Installation
See [Setup docs](SETUP.md).

### Basics
To profit from the basics, you can extend the controller and model class and include the Shim component.
```php
App::uses('ShimModel', 'Shim.Model');

class AppModel extends ShimModel {
}
```

```php
App::uses('ShimController', 'Shim.Controller');

class AppController extends ShimController {

	public $components = ['Shim.Shim'];

}
```

Testing has also been addressed.

For test cases you can extend the test case:
```php
App::uses('ShimTestCase', 'Shim.TestSuite');

class MyNameModelTest extends ShimTestCase {
}
```

For controller tests you can extend the 3.x backport of IntegrationTestCase:
```php
App::uses('ShimIntegrationTestCase', 'Shim.TestSuite');

class MyNameControllerTest extends ShimIntegrationTestCase {
}
```

### Quick-Start
For your bootstrap/Configure:
```php
Configure::write('Shim.warnAboutNamedParams', true);
Configure::write('Shim.warnAboutMissingContain', true);
Configure::write('Shim.monitorHeaders', true);
Configure::write('Shim.deprecateField', true);
Configure::write('Shim.deprecateSaveField', true);
Configure::write('Shim.disableRecursive', true);
```
and don't forget to include the Plugin bootstrap file.

In a cleanly written 2.x app there shouldn't be any errors visible now.
For all others it means some work to get to this ideal state.

See the detailed docs for explanations on each topic.

### Auth and Password Hashing
See [Auth](Auth.md).

### Model
See [Model](Model.md).

### View
See [View](View.md).

### Testing
See [Testing](Testing.md).

### More Features
See [Features](Features.md).

### More Ideas
See [MoreIdeas](MoreIdeas.md).
