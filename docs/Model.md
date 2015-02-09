## Model
... using ShimModel class.

### Status Quo
This class provides sane default settings for all 2.x apps, new as well as legacy ones (that need to be upgraded soon).
Containable behavior is attached by default, the recursive level is set to -1.

### get()
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

The main problem of `findById()` in 2.x is, that you cannot add any options, and also no
contain or recursive key - this would be vital to ensure 3.x portability, though.
With the second `$options` argument, you can easily "contain" or add other find() option keys.
And it also by default behaves like recursive -1, which is also nice.

#### record()
This method, as convenience wrapper, works like getById(), only with the full functionality from get() - and without the exception being thrown when
the record is not found.
```php
$record = $this->Post->record($id, $options);
if (!$record){
	return false;
}
...
```

Note: In 3.x you can then safely replace record() usage with findById() again wherever you didn't need the second options argument :)

### fieldByConditions()
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

### updateAll()/deleteAll()
In 3.x the updateAll() and deleteAll() won't auto-join anymore. In order to already write future proof versions of that you can use
- updateAllJoinless()
- deleteAllJoinless()
instead.
In 3.x all you need to do is rename them back again instead of tryig to fix all broken code.

There is also `deleteAllRaw()` in case you need an atomic wrapper for this, just as `updateAll()`/`updateAllJoinless()` is.

### Relation Setup wrappers
In 3.x you would set up the relations this way:
```php


```

### Behavior wrappers
In 3.x there will be a few wrappers on how to interact with behaviors. Those are shimmed with this plugin:

```php
// Adding
$this->ModelName->addBehavior('Tree');

// Checking
$this->ModelName->hasBehavior('Tree');

// Removing
$this->ModelName->removeBehavior('Tree');
```
