# Upgrade Guide

## Migration from 4.x to 5.x

### Removed shims of 3.x used in 4.x

- `FormHelper`

If you need any of that functionality, you will need to make your own app copy of
it.

::: info
A full overview of all shimming between versions can be found in the
[Wiki](https://github.com/dereuromark/cakephp-shim/wiki). When planning an
upgrade, also review the official
[upgrade docs](https://book.cakephp.org/5/en/appendices/5-0-upgrade-guide.html).
:::

## Preparing for CakePHP 6

Keep your application on the latest compatible CakePHP 5 release and run tests
with deprecation reporting enabled. This plugin currently requires CakePHP 5;
its Composer constraint does not declare CakePHP 6 compatibility.

Replace legacy entry points as the surrounding application code is migrated:

| Legacy usage | Core replacement on CakePHP 5 |
| --- | --- |
| `loadModel()` and dynamic model properties | `$table = $this->fetchTable('Articles')` |
| `$components` and `$helpers` properties | `loadComponent()` and `viewBuilder()->addHelpers()` |
| Pagination `contain` and `conditions` settings | Build a query with `contain()` and `where()`, then paginate it |
| Shim `saveAll()` | `saveMany()` or `saveManyOrFail()` |
| Shim `Inflector::slug()` | `Cake\Utility\Text::slug()` |
| `RequestHandler` view switching | Register view classes with `viewClasses()` |
| Request body decoding in `RequestHandler` | `BodyParserMiddleware` |

For new entity mass assignments, use `patch()` on CakePHP 5.2+, or individual
`set($field, $value)` calls when supporting earlier CakePHP 5 releases. For event
callbacks, set a result with `$event->setResult()` or stop propagation with
`$event->stopPropagation()` rather than returning a result.

Review the official [5.2 migration guide](https://book.cakephp.org/5/en/appendices/5-2-migration-guide.html)
and later minor-version guides for framework deprecations. Remove a shim only
after testing the replacement behavior in your application.

CakePHP 6 also renames protected framework methods and properties. Subclasses
such as the Shim table, paginator, and console output need a separate migration
when adding CakePHP 6 support. See the
[6.0 migration guide](https://book.cakephp.org/6.x/appendices/6-0-migration-guide.html)
for the current list; it may change before the final release.
