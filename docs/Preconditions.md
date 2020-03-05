# Pre-conditions

This plugin expects the following conditions to be met:

## IF upgrading existing 3.x app
- You are aware of all changes noted in the 3.x migration guides.
- You (ideally) already used the 3.x branch of this plugin for your 3.x app to ease migration - especially the defaults regarding recursive and contain.
- You coded "smart", avoiding deprecated or non-migratable coding practices as much as possible.
- Before actually migration you already used latest 3.x stable version and removed all deprecations in favor of the current recommendation.

These conditions make the migration process as smooth as possible.

See [Wiki](https://github.com/dereuromark/cakephp-shim/wiki) for all recommended pre-conditions.


Then, after upgrading and including this plugin:
- ...

## IF creating a new 4.x app
- Avoid using the 3.x shims and only use the 4.x convenience wrappers and functionality. They are marked as such.
Using BC shims on purpose is usually a bad idea and should be avoided from here on.
