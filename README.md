# Shim plugin for CakePHP
[![Build Status](https://api.travis-ci.com/dereuromark/cakephp-shim.svg?branch=master)](https://travis-ci.com/dereuromark/cakephp-shim)
[![Coverage](https://codecov.io/gh/dereuromark/cakephp-shim/branch/master/graph/badge.svg)](https://codecov.io/gh/dereuromark/cakephp-shim)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/cakephp-shim/v/stable.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-shim/license.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-shim/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

Shim plugin to "shim" functionality up and down for CakePHP major versions.
It also provides some small app-specific fixes.

## This branch is for shimming 3.x in 4.x
It provides compatibility wrapper access to 3.x functionality in 4.x.

This is mainly useful when upgrading large applications to the next major framework version.
Tons of code needs to be adjusted, using this Shim plugin quite a few lines less need to be touched.
Especially the ORM layer, which would need heavy refactoring, requires a lot less changes to get things working again.

This branch is for use with **CakePHP 4.0+**. For details see [version map](https://github.com/dereuromark/cakephp-shim/wiki#cakephp-version-map).

## Installation
Please see [Install.md](docs/Install.md)

## Usage
Please see [Docs](docs/).

A full overview of all shimming between versions can be found in the [Wiki](https://github.com/dereuromark/cakephp-shim/wiki).

## New shims
- Controller setup for components and helpers
- FormHelper BC for datetime ([details](https://github.com/dereuromark/cakephp-shim/pull/46)).

## Existing shims from 3.x
- Nullable behavior for better data consistency.
- `Table::field()` support and `fieldByConditions()` alias to migrate to.
- Still supports model properties `$primaryKey`, `$displayField`, `$order`, `$validate`, `$actsAs` and all
relations (`$belongsTo`, `$hasMany`, ...) as it would be very time-consuming to
manually adjust all those.
- Auto-adds Timestamp behavior if `created` or `modified` field exists in table.

## Helpful links
When planning to upgrade, you should look into [upgrade app for 3.x/4.x](https://github.com/dereuromark/upgrade) as well as the [rector tool](https://github.com/rectorphp/rector).
They both contain tons of more ideas on how to get code aligned with the current direction of the framework to reduce friction in the long run.
