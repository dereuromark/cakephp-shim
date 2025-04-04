# Number helper shim

In your AppView.php:
```php
$this->loadHelper('Shim.Number');
```
instead of the core one.

You have now the empty functionality of CakePHP 5.3+ in your Number helper:

- format()
- currency()

Instead of ternary checks you can pass your DB values directly, it will print out
an empty string (or defined default string) where needed.

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
