# Shim plugin for CakePHP
[![CI](https://github.com/dereuromark/cakephp-shim/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/dereuromark/cakephp-shim/actions/workflows/ci.yml?query=branch%3Amaster)
[![Coverage](https://codecov.io/gh/dereuromark/cakephp-shim/branch/master/graph/badge.svg)](https://codecov.io/gh/dereuromark/cakephp-shim)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/cakephp-shim/v/stable.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.5-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-shim/license.svg)](LICENSE)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-shim/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-purple.svg?style=flat-square)](https://github.com/php-fig-rectified/fig-rectified-standards)

Shim plugin to "shim" functionality up and down for CakePHP major versions.
It also provides some small app-specific fixes.

The published documentation covers the stable CakePHP 5 release. For this development branch, read the [branch documentation](docs/guide/index.md).

## This branch is for shimming legacy APIs in CakePHP 6
It provides compatibility wrappers for applications upgrading to CakePHP 6.

This is mainly useful when upgrading large applications to the next major framework version.
Tons of code needs to be adjusted, using this Shim plugin quite a few lines less need to be touched.
Especially the ORM layer, which would need heavy refactoring, requires a lot less changes to get things working again.

This branch is for use with **CakePHP 6.x** (PHP 8.5+). For details see [version map](https://github.com/dereuromark/cakephp-shim/wiki#cakephp-version-map).

"0-dependency plugin": Apart from core this plugin has no other dependencies!


Development dependencies use the CakePHP 6 migrations on upstream `dev-master`
branches once merged. Local sibling packages were used to verify the migrations
together before publication.

CakePHP 6 reserves the controller `$components` property for its registry.
Legacy component arrays now use `$legacyComponents`. Console test output is
stored in `$messages`, and command subclasses use parameterless `execute()`.

## Documentation

Full documentation lives at **[dereuromark.github.io/cakephp-shim](https://dereuromark.github.io/cakephp-shim/)**.

A few good entry points:

* [Overview](https://dereuromark.github.io/cakephp-shim/guide/)
* [Installation](https://dereuromark.github.io/cakephp-shim/guide/installation)
* [Getting started](https://dereuromark.github.io/cakephp-shim/guide/getting-started)
* [Upgrade Guide](https://dereuromark.github.io/cakephp-shim/guide/upgrade)
* [Database types](https://dereuromark.github.io/cakephp-shim/database/uuid)
* [Model and ORM shims](https://dereuromark.github.io/cakephp-shim/model/table)

The Markdown sources live in the [docs](docs/) directory of this repository.

A full overview of all shimming between versions can be found in the [Wiki](https://github.com/dereuromark/cakephp-shim/wiki).

## New shims
- find('list') shim for 1-2 select fields without requiring verbose keyField/valueField options.
- RequestHandler component for continued easier View class switching across the app
- LegacyModelAwareTrait for loadModel() shimming
- former Cake\Filesystem\File and Cake\Filesystem\Folder classes
- ModifiedTrait for entities and detecting actually changed fields (not just touched with same value)

Note: AuthComponent lives on in 5.x via [TinyAuth plugin](https://github.com/dereuromark/cakephp-tinyauth) if needed.

## Existing shims from 4.x
- Controller setup for components and helpers
- Nullable behavior for better data consistency.
- `Table::field()` support and `fieldByConditions()` alias to migrate to.
- Still supports model properties `$primaryKey`, `$displayField`, `$order`, `$validate`, `$actsAs` and all
relations (`$belongsTo`, `$hasMany`, ...) as it would be very time-consuming to
manually adjust all those.
- Auto-adds Timestamp behavior if `created` or `modified` field exists in table.

## Helpful links
When planning to upgrade, you should look into official [upgrade docs](https://book.cakephp.org/5/en/appendices/5-0-upgrade-guide.html) as well as the linked upgrade tool.
They both contain tons of more ideas on how to get code aligned with the current direction of the framework to reduce friction in the long run.
Also see my blog post [dereuromark.de/2023/09/28/cakephp-5-upgrade-guide/](https://www.dereuromark.de/2023/09/28/cakephp-5-upgrade-guide/).
