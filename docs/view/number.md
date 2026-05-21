# Number Helper

In your `AppView`:

```php
$this->loadHelper('Shim.Number');
```

instead of the core one.

You now have the empty-value handling of CakePHP 5.3+ in your `Number` helper:

- `format()`
- `currency()`

Instead of ternary checks you can pass your database values directly; it will
print out an empty string (or a defined default string) where needed.

```php
echo $this->Number->format($entity->value);
// or
echo $this->Number->format($entity->value, ['default' => '-']);
```

and

```php
echo $this->Number->currency($entity->value);
// or
echo $this->Number->currency($entity->value, ['default' => '-']);
```
