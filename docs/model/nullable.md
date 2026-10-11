# Nullable Behavior

The behavior replaces an empty string with the schema default only when the
column allows `NULL`. A nullable column with a `NULL` default becomes `null`;
a nullable column with a non-null default receives that default. Non-nullable
columns, unknown fields, and non-empty values are unchanged.

```php
// In your Table::initialize().
$this->addBehavior('Shim.Nullable');
```

For a nullable column with a `NULL` default, queries can check `IS NULL` after
normalization:

```php
$query->where(['field_name IS' => null]);
```

The behavior does not rewrite existing records. Clean up historical empty strings
separately if you need a consistent database value.

## Callback options

You can define the time this should happen.

::: warning Default callback affects validation
Be careful with the default setting (`beforeMarshal`), as this can affect
validation rules. Some rules use the empty-string existence to validate fields,
through `isset()` checks. Those checks might not work as expected if the input is
already back to `null`. There you would then need `array_key_exists()`.
:::

Another option is to apply the data-integrity cleanup on `beforeSave` instead:

```php
// In your Table
$this->addBehavior('Shim.Nullable', ['on' => 'beforeSave']);
```
