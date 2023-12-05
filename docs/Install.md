# Installation

## How to include
Installing the Plugin is pretty much as with every other CakePHP Plugin.

Install using Packagist/Composer:
```
composer require dereuromark/cakephp-shim
```

The following command can enable the plugin:
```
bin/cake plugin load Shim
```

Note: For most cases, the `loadPlugin()` part here isn't necessary. The elements of this plugin can be used directly where needed.
If nothing is used, nothing will be loaded out of the box. This makes this plugin quite safe to have around and only pick what you need.

## Testing MySQL

By default it will usually use SQLite DB (out of the box available).
If you want to run all tests, including MySQL ones, you need to set
```
export DB_URL="mysql://root:yourpwd@127.0.0.1/cake_test"
```
before you actually run
```
composer test
```

Make sure such a cake_test database exists.
