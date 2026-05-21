# Preconditions

This plugin expects the following conditions to be met for a smooth upgrade.

## If upgrading an existing 4.x app

- You are aware of all changes noted in the 4.x migration guides.
- You ideally already used the 4.x branch of this plugin for your 4.x app to ease
  migration — especially the defaults regarding recursive and `contain`.
- You coded "smart", avoiding deprecated or non-migratable coding practices as
  much as possible.
- Before actually migrating, you already ran the latest 4.x stable version and
  removed all deprecations in favor of the current recommendation.

These conditions make the migration process as smooth as possible.

::: tip
See the [Wiki](https://github.com/dereuromark/cakephp-shim/wiki) for all
recommended preconditions.
:::

## If creating a new 5.x app

- Avoid using the 4.x shims and only use the 5.x convenience wrappers and
  functionality. They are marked as such.

::: warning
Using BC shims on purpose is usually a bad idea and should be avoided from here
on.
:::
