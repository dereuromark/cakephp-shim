# Features

## Controller
You can also use `disableCache()` to auto-include `'Pragma' => 'no-cache'` which
shims it for older (IE) version to work there, as well.

Using `Configure::read('Shim.monitorHeaders')` you can monitor if all headers are properly
set via Response class and not for some reason sent prior to that.

Using `Configure::read('Shim.assertActionNames')` you can assert that all controller actions
have been properly migrated to the new camelBacked naming scheme. They must not be underscored anymore for DashedRoute setup.

## Component

Convenience class that automatically provides the component's methods with
the controller instance via `$this->Controller`. Extend is as follows:
```
namespace App\Controller\Component;

use Shim\Controller\Component\Component;

class MyComponent extends Component {
}
```

### Session component shim
The session should be used directly via `$this->request->session()` object.
But when upgrading you might want to keep the old way until you can refactor it fully:
```php
public $components = array('Shim.Session');
```
and you don't have to change your code.

## Helper

### Session helper shim
The session should be used directly via `$this->request->session()` object.
But when upgrading you might want to keep the old way until you can refactor it fully:
```php
public $helpers = array('Shim.Session');
```
and you don't have to change your code.
Note that this Session helper also provides an additional `consume()` method on top.

## Model
By using the Shim plugin Table class you can instantly re-use some 2.x behaviors.
This is super-useful when upgrading a very large codebase and you first need to get it to
run again - and afterwards want to start refactoring it.

It will by default look if it can re-use the following (if not nothing bad happens^^):
- `$primaryKey`
- `$displayField`,
- `$order` (also with correct auto-aliasing)
- `$validate` (needs minor adjustments)
- `$actsAs`
- all relations (`$belongsTo`, `$hasMany`, ...) as it would be very time-consuming to
manually adjust all those.

Also:
- Table::find('first') support.
- Table::find('count') support.
- Table::field() support and fieldByConditions() alias to migrate to.

It auto-adds Timestamp behavior if `created` or `modified` field exists in table.
To customize use `$this->createdField` or `$this->modifiedField` in your `initialize()` method
*before* calling `parent::initialize()`.
To deactivate simple set the two fields to `false`.

## Database

### UUID as BINARY(36)
Currently CakePHP still mainly only supports/promotes CHAR(36).
The BINARY types are much more [performant](http://iops.io/blog/storing-billions-uuid-fields-mysql-innodb/), though.

The clean way would be to mark those columns as type `uuid` manually:
```php
// In your Table class
protected function _initializeSchema(Table $table) {
	$table->columnType('id', 'uuid');
	...
	return $table;
}
```

But if you have many UUID columns for primary and foreign keys, you might want to use a more automatic approach.
If you upgrade 2.x apps that use the BINARY(36) type, you can use the Shim plugin's custom type class:

```php
// In your bootstrap
use Cake\Database\Type;
Type::map('binary', 'Shim\Database\Type\BinaryType');
```

Note: BINARY(16) would even be more performant, but then you would need to manually hex() and unhex() directly in the database.
So at this point this cannot be supported yet.

## Utility

Set class has been removed in favor of Hash. `pushDiff()` method has been dropped completely, though.
The Shim Set class provides this for easier migration.
