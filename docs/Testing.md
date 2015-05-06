## Testing

### IntegrationTestCase
Successor of the ControllerTestCase - which involved way too much mocking.

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
		$data = [
			'name' => 'foo bar'
		];
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
