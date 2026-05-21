# Nullable Behavior

The framework core in some places treats the incoming value too literally,
without introspecting the schema. The schema could say `NOT NULL`, meaning you do
not have to provide a string value. But if you do that, you easily end up with two
empty values: `null` and `''` (empty string).

You should attach the following behavior for better data consistency:

```php
// In your Table
$this->addBehavior('Shim.Nullable');
```

This makes sure empty strings posted will respect the nullable type of the table
schema. Your queries then only have to check for one type, instead of this code
smell:

```php
// check for empty
->where(['OR' => ['field_name IS' => null, 'field_name' => '']])

// check for non-empty
->where(['field_name IS NOT' => null, 'field_name !=' => ''])
```

Now it can always be this for `DEFAULT NOT NULL` database fields:

```php
// check for empty
->where(['field_name' => ''])

// check for non-empty
->where(['field_name !=' => ''])
```

And this for `DEFAULT NULL` database fields:

```php
// check for empty
->where(['field_name IS' => null])

// check for non-empty
->where(['field_name IS NOT' => null])
```

::: info
See [this ticket](https://github.com/cakephp/cakephp/issues/9678) for details and
the hope that this one day makes it into the core.
:::

## Callback options

You can define the time this should happen.

::: warning Default callback affects validation
Be careful with the default setting (`beforeMarshal`), as this can affect
validation rules. Some rules use the empty-string existence to validate fields —
through `isset()` checks. Those checks might not work as expected if the input is
already back to `null`. There you would then need `array_key_exists()`.
:::

Another option is to apply the data-integrity cleanup on `beforeSave` instead:

```php
// In your Table
$this->addBehavior('Shim.Nullable', ['on' => 'beforeSave']);
```
