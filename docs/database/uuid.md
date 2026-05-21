# UUID Type

## BinaryUuidType

### UUID as `BINARY(36)`

Currently CakePHP mainly supports and promotes `CHAR(36)`. The `BINARY` types are
much more
[performant](http://iops.io/blog/storing-billions-uuid-fields-mysql-innodb/),
though.

The clean way is to mark those columns as type `uuid` manually:

```php
// In your Table class
protected function _initializeSchema(Table $table) {
    $table->columnType('id', 'uuid');
    // ...
    return $table;
}
```

But if you have many UUID columns for primary and foreign keys, you might want a
more automatic approach. If you upgrade 2.x apps that use the `BINARY(36)` type,
you can use the Shim plugin's custom type class:

```php
// In your bootstrap
use Cake\Database\Type;

Type::map('binary', 'Shim\Database\Type\BinaryType');
```

::: tip `BINARY(16)` is even faster
`BINARY(16)` would be even more performant, but then you would need to manually
`hex()` and `unhex()` directly in the database. At this point that cannot be
supported here.
:::

### `BINARY(16)`

For this, please see `Cake\Database\Type\BinaryUuidType` that is now shipped by
default with the framework core.
