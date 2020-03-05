# Shim plugin - Usage

## Preconditions
See [Preconditions](Preconditions.md).

## Installation
* [Installation](Install.md)

## Upgrade Guide
* [Upgrade guide from 3.x to 4.x](Upgrade.md)

## Main shims
These will most likely all be ported over to future releases.

Controller
- [Asserting/Casting](Controller/CastTrait.md)
- [Component shims](Controller/Component.md)

Model
- [Table shim](Model/Table.md)
- [Entity Get/Fail](Model/Entity.md)
- [Nullable behavior](Model/Nullable.md)

Database
- [UUID type](Database/Uuid.md)
- [Time type](Database/Time.md)
- [Year type](Database/Year.md)
- [Array type](Database/Array.md)

TestSuite
- [Testing](TestSuite/Testing.md)

## BC shims
The following shims are only in place for 3.x => 4.x and can possibly be removed in the future.

Controller
- [Controller shims](Controller/Controller.md)

Helper
- [Configure helper](View/Configure.md)
- [Cookie helper](View/Cookie.md)
- [Form helper](View/Form.md)

Inflector
- [Inflector::slug()](Utility/Inflector.md)

## ORM

### Table
Extend the Shim plugin Table class to get the functionality:
```php
namespace App\Model\Table;

use Shim\Model\Table\Table as Table;

/**
 * App\Model\Table\MyTable class
 */
class MyTable extends Table {
}
```

In case you are using an app Table class yourself, you can just make this one extend the Shim plugin Table class:
```php
namespace App\Model\Table;

use Shim\Model\Table\Table as ShimTable;

/**
 * App\Model\Table\Table class
 */
class Table extends ShimTable {
}
```
And then all your tables can extend your own app Table class:
```php
namespace App\Model\Table;

/**
 * App\Model\Table\MyTable class
 */
class MyTable extends Table {
}
```

## Controller

### Component
You can extend the Shim plugin Component class to have the controller available inside by default:
```php
namespace Shim\Controller\Component;

use Shim\Controller\Component\Component;

/**
 * App\Controller\Component\MyController class
 */
class MyComponent extends Component {
}
```
