# Cache PhpEngine

Backports CakePHP 6.x `PhpEngine` to CakePHP 5 applications through this shim plugin.

It stores cache entries as executable PHP files and uses OPcache for faster reads than the regular file cache engine.

## Installation

The shim ships the class, but the exporter dependency stays optional.
If you want to use this engine in an app, add:

```bash
composer require brick/varexporter
```

## Usage

Configure a cache with the shimmed engine class:

```php
use Cake\Cache\Cache;
use Shim\Cache\Engine\PhpEngine;

Cache::setConfig('php_cache', [
	'className' => PhpEngine::class,
	'path' => CACHE . 'php/',
	'prefix' => 'myapp_',
	'duration' => '+1 year',
]);
```

This engine is best suited for mostly static caches such as metadata, routes, configuration, or attribute discovery.

It is not suited for atomic `increment()` / `decrement()` operations.
