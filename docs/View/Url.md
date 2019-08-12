# Url helper 

## Deprecation help

```php
public $helpers = ['Shim.Url'];
```

Make sure you loaded the plugin's bootstrap or manually set
```php
Configure::write('Shim.deprecations.urlBuild', true);
// or
Configure::write('Shim.deprecations', true);
```

You have now deprecation warnings for the deprecated bool part of $options:

```php
$this->Url->build($url, true);
$this->Url->build($url, false);
```

It should be refactored early on piece by piece to

```php
$this->Url->build($url, ['fullBase' => true]);
$this->Url->build($url, ['fullBase' => false]);
```
