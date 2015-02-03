## Model
... using ShimModel class.

# get()
This shims the 3.x pendant to 2.x.

Former way:
```php
App::uses('AppController', 'Controller');

class PostsController extends AppController {

	/**
	 * @throws NotFoundException If record cannot be found.
	 */
	public function view($id = null) {
		$record = $this->Post->findById($id);
		if (!$record) {
			throw new NotFoundException();
		}
		...
	}

}
```

New way:
```php
App::uses('AppController', 'Controller');

class PostsController extends AppController {

	/**
	 * @throws RecordNotFoundException If record cannot be found.
	 */
	public function view($id = null) {
		$record = $this->Post->get($id);
		...
	}
}
```

# fieldByConditions()
This method is a better fit than the current field() method, as it provides a way to add $options (and contain in general).
Please switch from field() to this method when using the shims. It will help ease the migration towards 3.x.

Former way:
```php
$conditions = ['id' => $id];
$record = $this->Post->field('name', $conditions, $order);
// $record contains the string "FooBar" etc
```

New way:
```php
$conditions = ['id' => $id];
$record = $this->Post->fieldByConditions('name', $conditions, $options);
// $record contains the string "FooBar" etc
```
or even
```php
$conditions = ['id' => $id];
$options = ['order' => $order, 'contain' => [...], ...];
$record = $this->Post->fieldByConditions('name', $conditions, $options);
// $record contains the string "FooBar" etc
```
