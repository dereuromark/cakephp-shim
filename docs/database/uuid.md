# UUID Type

## Legacy BINARY(36) columns

### UUID as `BINARY(36)`

This shim supports existing `BINARY(36)` columns containing textual UUIDs. Use the core `binaryuuid` type for new `BINARY(16)` columns.

The clean way is to mark those columns as type `uuid` manually:

```php
// In your Table::initialize(), after parent::initialize($config).
$this->getSchema()->setColumnType('id', 'uuid');
```

But if you have many UUID columns for primary and foreign keys, you might want a
more automatic approach. If you upgrade 2.x apps that use the `BINARY(36)` type,
you can use the Shim plugin's custom type class:

```php
// In your bootstrap
use Cake\Database\TypeFactory;

TypeFactory::map('binary', 'Shim\Database\Type\BinaryType');
```

### `BINARY(16)`

For this, please see `Cake\Database\Type\BinaryUuidType` that is now shipped by
default with the framework core. It handles conversion between textual UUIDs
and 16-byte database values. Set the column type to `binaryuuid`; no Shim type
registration is needed.
