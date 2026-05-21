# Installation

## How to include

Installing the plugin is the same as for any other CakePHP plugin.

Install via Composer:

```bash
composer require dereuromark/cakephp-shim
```

The following command can enable the plugin:

```bash
bin/cake plugin load Shim
```

::: tip Loading is usually optional
For most cases the `plugin load` step is **not** necessary. The elements of this
plugin can be used directly where needed. If nothing is used, nothing is loaded
out of the box — which makes the plugin quite safe to have around so you can pick
only what you need.
:::

## Testing with MySQL/Postgres

By default the test suite uses SQLite, which is available out of the box.

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
