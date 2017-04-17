# Installation

## How to include
Installing the Plugin is pretty much as with every other CakePHP Plugin.

Install using Packagist/Composer:
```
composer require dereuromark/cakephp-shim
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

This will load the plugin (within your bootstrap file):
```php
Plugin::load('Shim');
```
or
```php
Plugin::loadAll(...);
```

There is also a handy shell command that can enable a plugin:
```
bin/cake plugin load Shim
```

## Notes
Please don't forget that your application's composer.json file needs to require [cakephp/plugin-installer](https://github.com/cakephp/plugin-installer):
```
composer require cakephp/plugin-installer:*
```
