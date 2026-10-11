# Installation

## Install

Installing the plugin is the same as for any other CakePHP plugin.

Install via Composer:

```bash
composer require dereuromark/cakephp-shim:dev-cake6
```

The following command can enable the plugin:

```bash
bin/cake plugin load Shim
```

::: tip Loading is usually optional
Classes, traits, and plugin aliases such as `Shim.Nullable` or `Shim.Number`
work through Composer autoloading without the `plugin load` step.
Loading runs its bootstrap, which enables `Shim.deprecations` and defines legacy
time constants when they are missing. The elements of this
plugin can be used directly where needed. If nothing is used, nothing is loaded
out of the box, so you can pick
only what you need.
:::

See [Getting started](/guide/getting-started) for a complete example.

## Testing with MySQL/Postgres

These commands run the plugin repository test suite, not your application tests.
Install its development dependencies with `composer install` first. The default
suite uses SQLite and requires `pdo_sqlite`.

To run all tests including the MySQL ones, set:

```bash
export DB_URL="mysql://root:yourpwd@127.0.0.1/cake_test"
```

To run all tests including the Postgres ones, set:

```bash
export DB_URL="postgres://postgres@127.0.0.1/postgres"
```

Then run:

```bash
composer test
```

::: warning
Make sure the corresponding `cake_test` or `postgres` database exists before
running the suite.
:::
