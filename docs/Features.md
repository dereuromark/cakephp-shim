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
- Table::saveField() support.
- Table::saveArray() support.
- Table::existsById() support for migrating from 2.x

It auto-adds Timestamp behavior if `created` or `modified` field exists in table.
To customize use `$this->createdField` or `$this->modifiedField` in your `initialize()` method
*before* calling `parent::initialize()`.
To deactivate simple set the two fields to `false`.

### IN / NOT IN
Those where quite handy in 2.x, any `'field' => $scalarOrArray` would be automatically converted.
This has been removed in 3.x and you manually have to decide if you need to add `IN`/`NOT IN`.
When upgrading from 2.x, the following snippets can help ease migration (especially if the counter part has been applied to 2. already):
```php
// In Table class
$query = $this->...();

// This modifies the query right away
$query->where($this->arrayCondition($query, 'field', $scalarOrArray)]);

// You can also use the 2.x array shim, which returns an array
$query->where($this->arrayConditionArray('field', $scalarOrArray)]);
```
It also makes the statement in general more correct, as `NOT IN []` would technically mean to not find anything, but the 2.x API
so far used `IS NULL`, which could false positive find nullish records.
The main reason this is also useful for 3.x is the [hidden time-bomb](https://github.com/dereuromark/cakephp-upgrade/wiki/Upgrading-Notes-for-CakePHP-3.x#query-conditions)
in the ORM when array can become empty and an exception is being thrown.

Of course this shimmed functionality and `NOT` clauses with deleting records can also be dangerous and in some cases can lead to accidental truncation of tables if not carefully
applied. In most cases one would not want the sudden 0 exception, though, and you either manually add !empty() checking or you use this functionality when you are sure it is safe
to do so.

### saveAll() and transactions
Table:saveAll() wraps saving multiple entities. Optionally, you can wrap them to be transaction safe together:
```php
// In a controller.
$articles->connection()->transactional(function () use ($articles, $entities) {
    $articles->saveAll($entities, ['atomic' => false]);
}
```
Note: In 4x. this will be coming as `saveMany()`/`saveManyOrFail()`.


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

### Set
Set class has been removed in favor of Hash. `pushDiff()` method has been dropped completely, though.
The Shim Set class provides this for easier migration.
