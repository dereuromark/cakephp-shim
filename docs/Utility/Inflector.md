# Inflector Utility class

## FC shim
An Inflector backport has been added as opt-in replacement for the core one.
It brings the 4.x inflector behavior to 3.x:
- `pluralize()`: `index` => `indexes` (instead of `indices`)

You can also configure the core one with custom inflections from your bootstrap, though.

This shim class will be removed in 4.x.
