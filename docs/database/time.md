# Time Type

## TimeStringType

The `TimeStringType` can be used to represent a specific string field for
`HH:MM:SS` times. It keeps the string type, but makes sure the format is
normalized and valid.

Besides strings, it accepts arrays from legacy dropdown controls. CakePHP 5
uses an HTML time input by default, so submit a full `HH:MM:SS` string; a value
without seconds is rejected by this type.

```php
$data = [
    // ...
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
// In your Table::initialize(), after parent::initialize($config).
$this->getSchema()->setColumnType('closing_time', 'time');
```

Then make sure you mapped the type to a class in your config:

```php
// In your bootstrap
use Cake\Database\TypeFactory;

TypeFactory::map('time', 'Shim\Database\Type\TimeStringType');
```

If you want to disable `24:00:00` as the valid upper boundary (and transform it
to `00:00:00` instead):

```php
// In your bootstrap
\Shim\Database\Type\TimeStringType::$normalizeUpperBoundary = true;
```

## Invalid values

Invalid strings become `null`. Missing array parts default to `00`. Add validation
if an invalid or missing time must prevent a save. Mapping `time` changes all
columns using that type; use a separate type name and assign it to selected
columns when you need the core time type elsewhere.
