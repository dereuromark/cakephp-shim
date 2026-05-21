# Table

## BC methods and properties

By using the Shim plugin `Table` class you can instantly re-use some 2.x
behaviors. This is super useful when upgrading a very large codebase and you
first need to get it running again — and afterwards want to start refactoring.

By default it will look if it can re-use the following (if not, nothing bad
happens):

- `$primaryKey`
- `$displayField`
- `$order` (also with correct auto-aliasing; also works inside `contain`)
- `$validate` (needs minor adjustments)
- `$actsAs`
- all relations (`$belongsTo`, `$hasMany`, ...), as it would be very
  time-consuming to manually adjust all those

Also:

- `Table::field()` support and a `fieldByConditions()` alias to migrate to.

It auto-adds the `Timestamp` behavior if a `created` or `modified` field exists in
the table. To customize, use `$this->createdField` or `$this->modifiedField` in
your `initialize()` method *before* calling `parent::initialize()`. To
deactivate, simply set the two fields to `false`.

## IN / NOT IN

These were quite handy in 2.x: any `'field' => $scalarOrArray` would be
automatically converted. This was removed in 3.x and you manually have to decide
if you need to add `IN`/`NOT IN`. When upgrading from 2.x, the following snippets
can help ease migration (especially if the counterpart has been applied to 2.x
already):

```php
// In Table class
$query = $this->...();

// This modifies the query right away
$query->where($this->arrayCondition($query, 'field', $scalarOrArray));

// You can also use the 2.x array shim, which returns an array
$query->where($this->arrayConditionArray('field', $scalarOrArray));
```

It also makes the statement more correct in general: `NOT IN []` would
technically mean to find nothing, but the 2.x API so far used `IS NULL`, which
could falsely match nullish records. The main reason this is also useful for 3.x
is the
[hidden time-bomb](https://github.com/dereuromark/cakephp-upgrade/wiki/Upgrading-Notes-for-CakePHP-3.x#query-conditions)
in the ORM when an array can become empty and an exception is thrown.

::: danger Use with care
This shimmed functionality, and `NOT` clauses with deleting records, can be
dangerous and in some cases lead to accidental truncation of tables if not
carefully applied. In most cases you would not want the sudden empty-array
exception, though — so either add manual non-empty checks or use this
functionality only when you are sure it is safe.
:::

## saveMany() and transactions

`Table::saveMany()` wraps saving multiple entities. Optionally, you can wrap them
to be transaction safe together:

```php
// In a controller.
$articles->connection()->transactional(function () use ($articles, $entities) {
    $articles->saveMany($entities, ['atomic' => false]);
});
```

::: tip
Use `saveManyOrFail()` if you want to throw an exception instead.
:::

## Extending the Table class

Extend the Shim plugin `Table` class to get the functionality:

```php
namespace App\Model\Table;

use Shim\Model\Table\Table as Table;

/**
 * App\Model\Table\MyTable class
 */
class MyTable extends Table {
}
```

In case you are using an app `Table` class yourself, you can just make that one
extend the Shim plugin `Table` class:

```php
namespace App\Model\Table;

use Shim\Model\Table\Table as ShimTable;

/**
 * App\Model\Table\Table class
 */
class Table extends ShimTable {
}
```

And then all your tables can extend your own app `Table` class:

```php
namespace App\Model\Table;

/**
 * App\Model\Table\MyTable class
 */
class MyTable extends Table {
}
```
