# Installation

## How to include
Installing the Plugin is pretty much as with every other CakePHP Plugin.

Install using Packagist/Composer:
```
composer require dereuromark/cakephp-shim:dev-master
```

or manually via

```
"require": {
	"dereuromark/cakephp-shim": "dev-master"
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
