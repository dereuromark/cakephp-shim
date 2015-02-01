## Shim plugin Documentation

### Installation
usin composer and `composer require dereuromark/cakephp-shim:[version].*`:

```
"require": {
	"dereuromark/cakephp-tools": "[version].*"
}
```
This will install the plugin into APP/Vendor.

Make sure you have `CakePlugin::load('Tools')` or `CakePlugin::loadAll()` in your bootstrap.

If you want to leverage the defaults regarding query strings, use
```
CakePlugin::load('Tools', ['bootstrap' => true]);
```

### Basics
To profit from the basics, you can extend the controller and model class and include the component.
```
App::uses('ShimModel', 'Shim.Model');

class AppModel extends ShimModel {
}
```

```
App::uses('ShimController', 'Shim.Controller');

class AppController extends ShimController {

	public $components = ['Shim.Shim'];

}
```


For test cases you can extend the test case:
```
App::uses('ShimTestCase', 'Shim.TestSuite');

class MyNameModelTest extends ShimTestCase {
}
```

For controller tests you can extend the 3.x backport of IntegrationTestCase:
```
App::uses('ShimIntegrationTestCase', 'Shim.TestSuite');

class MyNameControllerTest extends ShimIntegrationTestCase {

	...

	public function testIndex() {
		$this->get(['controller' => 'my_name', 'action' => 'index']);
		$this->assertResponseCode(200);
		$this->assertNoRedirect();
	}

	public function testAddPost() {
		$data = array(
			'name' => 'foo bar'
		);
		$this->post(['controller' => 'my_name', 'action' => 'add'], $data);
		$this->assertResponseCode(302);
		$this->assertRedirect(['controller' => 'my_name', 'action' => 'index']);
		$this->assertSession('Saved!'), 'Message.flash.message');
	}

	...

}
```

### Auth and Password Hashing
See [Auth](Auth.md)

### More Features
See [Features](Features.md).
