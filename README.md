# Shim plugin for CakePHP
[![CI](https://github.com/dereuromark/cakephp-shim/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/dereuromark/cakephp-shim/actions/workflows/ci.yml?query=branch%3Amaster)
[![Coverage](https://codecov.io/gh/dereuromark/cakephp-shim/branch/master/graph/badge.svg)](https://codecov.io/gh/dereuromark/cakephp-shim)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/cakephp-shim/v/stable.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-shim/license.svg)](LICENSE)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-shim/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

Shim plugin to "shim" functionality up and down for CakePHP major versions.
It also provides some small app-specific fixes.

## This branch is for shimming 4.x in 5.x
It provides compatibility wrapper access to 4.x functionality in 5.x.

This is mainly useful when upgrading large applications to the next major framework version.
Tons of code needs to be adjusted, using this Shim plugin quite a few lines less need to be touched.
Especially the ORM layer, which would need heavy refactoring, requires a lot less changes to get things working again.

This branch is for use with **CakePHP 5.0+**. For details see [version map](https://github.com/dereuromark/cakephp-shim/wiki#cakephp-version-map).

## Installation
Please see [Install.md](docs/Install.md)

## Usage
Please see [Docs](docs/README.md).

A full overview of all shimming between versions can be found in the [Wiki](https://github.com/dereuromark/cakephp-shim/wiki).

## New shims
- LegacyModelAwareTrait for loadModel() shimming
- former Cake\Filesystem\File and Cake\Filesystem\Folder classes
- ModifiedTrait for entities and detecting actually changed fields (not just touched with same value)

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
