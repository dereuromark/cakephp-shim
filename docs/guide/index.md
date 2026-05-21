# Overview

The **Shim** plugin "shims" functionality up and down across CakePHP major
versions. It also provides a handful of small, app-friendly convenience
wrappers.

This is mainly useful when **upgrading large applications** to the next major
framework version. A lot of code usually needs to be adjusted; with this plugin
fewer lines need to be touched. Especially the ORM layer — which would otherwise
require heavy refactoring — needs far fewer changes to get things working again.

::: tip Zero-dependency plugin
Apart from the framework core, this plugin has **no other dependencies**. It is
safe to have around: nothing is loaded out of the box, so you only pick what you
need.
:::

::: info CakePHP version
This documentation is for the branch that shims **4.x in 5.x** and targets
**CakePHP 5.1+**. For older releases, see the
[version map](https://github.com/dereuromark/cakephp-shim/wiki#cakephp-version-map).
:::

## How it works

The plugin is built so that nothing happens unless you opt in. For most cases you
do not even need to load the plugin — the individual elements (database types,
traits, behaviors, helpers) can be used directly where needed.

There are two broad categories of shims:

- **Main shims** — convenience functionality that will most likely be ported
  forward into future releases. Safe to adopt long term.
- **BC shims** — backwards-compatibility helpers that only exist to ease the
  3.x → 4.x → 5.x transition. These are intended to be removed again once your
  upgrade is complete.

::: warning Use BC shims deliberately
Using backwards-compatibility shims on purpose in new code is usually a bad idea.
They exist to keep an in-flight upgrade running, not to be a permanent crutch.
:::

## The pieces

| Area | What the shim provides |
|------|------------------------|
| [Database](/database/uuid) | Custom column types: UUID (binary), time-as-string, year, and array. |
| [Datasource](/datasource/numeric-paginator) | A paginator that still understands `contain`/`conditions`, plus `loadModel()` shimming. |
| [Model](/model/table) | A base `Table` re-using legacy properties, entity read/require/get-set traits, and a `Nullable` behavior. |
| [Controller](/controller/) | A base `Controller`, the `CastTrait`, and an out-of-bounds pagination redirect trait. |
| [Component](/component/request-handler) | A `RequestHandler` component for continued automatic view-class switching. |
| [View](/view/configure) | `Configure`, `Cookie`, and `Number` helpers. |
| [Utility](/utility/inflector) | `Inflector::slug()` BC wrapper. |
| [Testing](/testing/) | A `TestTrait`, `ConsoleOutput`, and integration/test case base classes. |

## Next steps

- [Installation](/guide/installation) — install the plugin via Composer.
- [Preconditions](/guide/preconditions) — what to have in place before upgrading.
- [Upgrade Guide](/guide/upgrade) — migrating from 4.x to 5.x.

## Helpful links

When planning an upgrade, also look into the official
[upgrade docs](https://book.cakephp.org/5/en/appendices/5-0-upgrade-guide.html)
and the linked upgrade tool. They contain many more ideas on how to align code
with the current direction of the framework and reduce friction long term. See
also the blog post on the
[CakePHP 5 upgrade guide](https://www.dereuromark.de/2023/09/28/cakephp-5-upgrade-guide/).

A full overview of all shimming between versions can be found in the
[Wiki](https://github.com/dereuromark/cakephp-shim/wiki).
