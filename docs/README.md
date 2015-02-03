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

### Auth and Password Hashing
See [Auth](Auth.md).

### Model
See [Model](Model.md).

### Testing
See [Testing](Testing.md).

### More Features
See [Features](Features.md).
