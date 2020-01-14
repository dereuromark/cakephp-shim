# Time Type class

## TimeStringType
The `TimeStringType` can be used to represent a specific string field for `HH:MM:SS` times.
It keeps the string type, but makes sure the format is normalized and valid.

Besides string input, it can also accept the Cake dropdown select values from a default time control:

```php
$data = [
    ...
    'closing_time' => [
        'hour' => '1',
        'minute' => '12',
        'second' => '20',
    ],
];
$entity = $this->Table->newEntity($data);
// $entity->closing_time is now 01:12:20
```

To set it up for a specific field:
```php
// In your Table class
protected function _initializeSchema(Table $table) {
    $table->columnType('closing_time', 'time');
    ...
    return $table;
}
```

Then make sure you mapped the type to a class in your config:
```php
// In your bootstrap
use Cake\Database\Type;
Type::map('time', 'Shim\Database\Type\TimeStringType');
```

If you want to disable `24:00:00` as valid upper boundary (and transform it to `00:00:00` instead):
```php
// in your bootstrap
\Shim\Database\Type\TimeStringType::$normalizeUpperBoundary = true;
```


## What about objects?
We could also think about a value object representing time.
This way you could offer a bit more functionality:
- format() with e.g. only `HH:MM` etc - but most of this can already be done on presentation layer via helper
- diff() in `HH:MM::SS` or `\DateInterval`
- isSame()/isBefore()/isAfter() methods
- fromDateTime() and alike to extract the time part

Even custom localized formatting like `AM/PM` could be added, but I think this should really be kept rather
in the presentation layer to keep such logic out of the value object itself.

Feel free to add a `TimeObjectType` class here.
