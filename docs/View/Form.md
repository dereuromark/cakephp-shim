# Form helper shim

## Complete Shim
In your AppView.php:
```php
$this->loadHelper('Shim.Form');
```

This uses the 3.x way of creating form elements.
Especially around datetime handling.

## Per Widget
You can also use the new form helper and only switch out single widgets,
e.g. the DateTime one from the (buggy) HTML5 back to dropdowns or strings.

For this just configure the helper accordingly:
```php
$this->loadHelper('Form', [
    'widgets' => [
        'datetime' => ['Shim\View\Widget\DateTimeWidget', 'select'],
    ],
]);
```
