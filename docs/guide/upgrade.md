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

## Upgrading to CakePHP 6

This development branch supports CakePHP 6. Install `dev-cake6` as described in
[Installation](/guide/installation). Stable Shim 3.x releases remain available
for CakePHP 5 applications.

CakePHP 6 reserves the controller `$components` property for its registry.
Legacy component arrays in Shim controllers use `$legacyComponents`. Prefer
`loadComponent()` and `viewBuilder()->addHelpers()` for new code.

Commands use parameterless `execute()` methods. Console test output is stored
in `$messages`. For event callbacks, set a result with `$event->setResult()` or
stop propagation with `$event->stopPropagation()`.

Use entity `patch()` for mass assignment and `$patchable` for accessible-field
rules. Keep individual `set($field, $value)` calls for direct assignment.

Review the official
[6.0 migration guide](https://book.cakephp.org/6.x/appendices/6-0-migration-guide.html)
for framework changes; the development APIs may change before the final release.
