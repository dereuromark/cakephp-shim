## Shim plugin Documentation

### Installation
See [Setup docs](SETUP.md).

### Basics
To profit from the basics, you can extend the controller and model class and include the component.
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

	...

	public function testIndex() {
		$this->get(['controller' => 'my_name', 'action' => 'index']);
		$this->assertResponseCode(200);
		$this->assertNoRedirect();
		$this->assertResponseNotEmpty();
        $this->assertResponseContains('Some HTML snippet or text.');
	}

	public function testAddPost() {
		$data = array(
			'name' => 'foo bar'
		);
		$this->post(['controller' => 'my_name', 'action' => 'add'], $data);
		$this->assertResponseCode(302);
		$this->assertResponseEmpty();
		$this->assertRedirect(['controller' => 'my_name', 'action' => 'index']);
		$this->assertSession('Saved!'), 'Message.flash.message');
	}

	...

}
```
This uses the same syntax as in 3.x then. When upgrading your 2.x app you will
not have to touch this much then. A real time saver when doing a lot of integration testing.

### Auth and Password Hashing
See [Auth](Auth.md)

### More Features
See [Features](Features.md).
