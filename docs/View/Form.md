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

If you are not using the Shim helper, but the widgets standalone with core Form helper, you will
have to extend the FormHelper on app level and overwrite a few methods in order to retain full BC
(and configuration):

```php
    // \App\View\Helper\FormHelper extends \Cake\View\Helper\FormHelper
	public function dateTime(string $fieldName, array $options = []): string {
		$options += [
			'empty' => true,
			'value' => null,
			'interval' => 1,
			'round' => null,
			'monthNames' => true,
			'minYear' => null,
			'maxYear' => null,
			'orderYear' => 'desc',
			'timeFormat' => 24,
			'second' => false,
		];

		return parent::dateTime($fieldName, $options);
	}
```
