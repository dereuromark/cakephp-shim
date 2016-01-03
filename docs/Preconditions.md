# Pre-conditions

This plugin expects the following conditions to be met:

## IF upgrading existing 2.x app
- You are aware of all changes noted in the 2.x migration guides.
- You (ideally) already used the 2.x branch of this plugin for your 2.x app to ease migration - especially the defaults regarding recursive and contain.
- You coded "smart", avoiding deprecated or non-migratable coding practices as much as possible.
- Before actually migration you already used latest 2.x stable version and removed all deprecations in favor of the current recommendation.
- Also make sure you always added missing `App::uses()` statements to the top of each file. Every missing one (or in the middle of the code) will mean more work in 3.x.

These conditions make the migration process as smooth as possible.

Furthermore, the following changes should to be made prior to upgrading to 3.x and using this plugin:
- The code contains no more `->contain()` or `->recursive` code. They will not work - or more like break everything.
- The code must not contain any `saveField()` usage anymore.
- Any usage of `Model::field()` must have been (str_replace?) replaced with `Model::fieldByConditions`.
- Any usage of `read()` must have been replaced by `get()` or at least `record()`, the exception free convenience wrapper.
- Any usage of `updateAll()` or `deleteAll()` that does not require joins should have been rewritten to the ~Joinless() variants. This
eases the migration of the remaining ones, which most likely will need to be adjusted, as in 3.x they don't auto-join anymore.

See [Wiki](https://github.com/dereuromark/cakephp-shim/wiki) for all recommended pre-conditions.


Then, after upgrading and including this plugin:
- You may change any usage of `record()` back to `findById()`, if you didn't use any `$options`. But it can also stay this way for easier
modification later on.
- You can rewrite any `fieldByConditions()` to `field()` when also adjusting `$conditions` to `$options`.
- You can rewrite any `saveFieldById()` to `saveField()` when also adjusting `$conditions` to `$options`.
- You can rename the ~Joinless() variants to the actual ones again once you cleaned up all join-occurrences.

## IF creating a new 3.x app
- Avoid using the 2.x shims and only use the 3.x convenience wrappers and functionality. They are marked as such.
Using BC shims on purpose is usually a bad idea and should be avoided from here on.
