# Url helper 

## Deprecation help

```php
public $helpers = ['Shim.Url'];
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
