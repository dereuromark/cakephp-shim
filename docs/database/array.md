# Array Type

`ArrayType` passes arrays and strings through marshalling and database reads.
On database writes, it accepts strings and `null`; an array becomes `null`.
It does not JSON encode or decode values.

Register it in your application bootstrap:

```php
use Cake\Database\TypeFactory;
use Shim\Database\Type\ArrayType;

TypeFactory::map('array', ArrayType::class);
```

In your table's `initialize()`, set the column type with
`$this->getSchema()->setColumnType('column_name', 'array')`. A behavior must encode the array before saving.
For automatic JSON conversion in new code, use CakePHP's core `json` type.

See the [Tools](https://github.com/dereuromark/cakephp-tools) plugin's `Jsonable`
behavior when maintaining an application that already uses behavior-based encoding.
