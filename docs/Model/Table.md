# Table shims

## BC methods and properties
By using the Shim plugin Table class you can instantly re-use some 2.x behaviors.
This is super-useful when upgrading a very large codebase and you first need to get it to
run again - and afterwards want to start refactoring it.

It will by default look if it can re-use the following (if not nothing bad happens^^):
- `$primaryKey`
- `$displayField`,
- `$order` (also with correct auto-aliasing, also works inside contain)
- `$validate` (needs minor adjustments)
- `$actsAs`
- all relations (`$belongsTo`, `$hasMany`, ...) as it would be very time-consuming to
manually adjust all those.

Also:
- `Table::field()` support and `fieldByConditions()` alias to migrate to.
- `Table::saveField()` support.
- `Table::saveArray()` support.
- `Table::existsById()` support for migrating from 2.x

It auto-adds Timestamp behavior if `created` or `modified` field exists in table.
To customize use `$this->createdField` or `$this->modifiedField` in your `initialize()` method
*before* calling `parent::initialize()`.
To deactivate simple set the two fields to `false`.

## IN / NOT IN
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

## saveMany() and transactions
`Table:saveMany()` wraps saving multiple entities. Optionally, you can wrap them to be transaction safe together:
```php
// In a controller.
$articles->connection()->transactional(function () use ($articles, $entities) {
    $articles->saveMany($entities, ['atomic' => false]);
}
```
Note: Use `saveManyOrFail()` if you want to throw exception instead.
