# Installation

## How to include
Installing the Plugin is pretty much as with every other CakePHP Plugin.

Install using Packagist/Composer:
```
composer require dereuromark/cakephp-shim:0.1.*
```

or manually via

```
"require": {
	"dereuromark/cakephp-shim": "0.1.*"
}
```
and

	composer update

Details @ https://packagist.org/packages/dereuromark/cakephp-shim

This will load the plugin (within your boostrap file):
```php
Plugin::load('Shim');
```
or
```php
Plugin::loadAll(...);
```

In case you want the Shim bootstrap file included (recommended), you can do that in your `ROOT/config/bootstrap.php` with

```php
Plugin::load('Shim', array('bootstrap' => true));
```

or

```php
Plugin::loadAll(array(
		'Shim' => array('bootstrap' => true)
));
```

That bootstrap file will set config defaults to use pagination with query strings.
