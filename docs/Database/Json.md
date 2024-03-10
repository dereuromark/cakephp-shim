# Json Type class

## JsonType
The `JsonType` is an extension from core and allows setting the encoding options
more globally:

- Configure key `'Shim.jsonEncodingOptions'` can be used to set it as an int of flags, e.g.

```php
// bootstrap
use Shim\Database\Type\JsonType;

Cake\Database\TypeFactory::map('json', JsonType::class);
```
and
```php
// config app.php
'Shim' => [
    'jsonEncodingOptions' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
],
```
