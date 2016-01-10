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
public function initialize(array $config) {
	$this->hasOne('Addresses', [
		'dependent' => true
	]);
	...
}
```
You can now do that in 2.x already, as well.

### IN / NOT IN
First of all, auto-IN is not supported anymore in 3.x.
You would need to manually say `'field IN' => $array`. In In general this is a good thing. Being more explicit
if you want a string or array to be checked is a big plus. The downside is the time-bomb issue when the array becomes
empty for some reason and an exception is suddenly thrown.
While this aims to increase security this can also contain hidden bugs in your app.

The 2nd issue is that the current behavior in 2.x is `IS NULL`/`IS NOT NULL` for empty arrays.
This is not ideal and works as long as you are not working with optional foreign keys or nullable fields.
Then you might get false positives.

Thus, to ease migration into 3.x and already use the more correct `1!=1`/`1=1` for empty arrays, you can leverage
```php
// in Model
$array = [1, 3];
'conditions' => $this->arrayConditionArray('field', $array)
// Becomes: WHERE field IN (1, 2)

$array = [];
'conditions' => $this->arrayConditionArray('field', $array)
// Becomes: WHERE 1!=1
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

### TreeBehavior
`generateTreeList()` will be gone in 3.x. Use the custom `treeList` finder already instead:
```php
$treeList = $this->Category->find('treeList', ['spacer' => '-']);
```

Same for `children()` and `getPath()`, which will also be changed to custom finders in 3.x:
```php
$path = $this->Category->find('path', ['id' => $id]);
$children = $this->Category->find('children', ['id' => $id]);
```

All other 3.x options (fields, conditions, order, ...) are now supported using the 2nd `$options` array.

Note that fo all these new custom finders you have to load the TreeBehavior on this model, first, though (just as you would with the
former methods).
