# Shim plugin for CakePHP
[![Build Status](https://api.travis-ci.org/dereuromark/cakephp-shim.svg?branch=master)](https://travis-ci.org/dereuromark/cakephp-shim)
[![Coverage](https://codecov.io/gh/dereuromark/cakephp-shim/branch/master/graph/badge.svg)](https://codecov.io/gh/dereuromark/cakephp-shim)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/cakephp-shim/v/stable.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-shim/license.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-shim/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

Shim plugin to "shim" functionality up and down for CakePHP major versions.
It also provides some small app-specific fixes.

## This branch is for shimming 2.x in 3.x
It provides compatibility wrapper access to 2.x functionality in 3.x.

This is mainly useful when upgrading large applications to the next major framework version.
Tons of code needs to be adjusted, using this Shim plugin quite a few lines less need to be touched.
Especially the ORM layer, which would need heavy refactoring, requires a lot less changes to get things working againc.

**This branch requires CakePHP 3.7+**

## Installation
Please see [SETUP.md](docs/SETUP.md)

## Usage
Please see [Docs](docs).

A full overview of all shimming between 2.x and 3.x can be found in the [Wiki](https://github.com/dereuromark/cakephp-shim/wiki).

## Main shims
- Common component and Nullable behavior for better data consistency.
- TraitCast for clean and safe request handling.
- Auto-adds Timestamp behavior if `created` or `modified` field exists in table.
- Get/Read trait for clean and speaking entity access.

## BC shims (only for 2.x => 3.x)
- Contains Session component as compatibility wrapper for request session object (and Session helper).
- Primary level `Table::find('first')` support.
- Primary level `Table::find('count')` support.
- `Table::field()` support and `fieldByConditions()` alias to migrate to.
- Still supports model properties `$primaryKey`, `$displayField`, `$order`, `$validate`, `$actsAs` and all
relations (`$belongsTo`, `$hasMany`, ...) as it would be very time-consuming to
manually adjust all those.

## Deprecation help (for 3.x => 4.x)
- `UrlHelper::build()`

## Helpful links
When planning to upgrade, you should look into the [upgrade plugin for 2.x](https://github.com/dereuromark/cakephp-upgrade) and [upgrade app for 3.x](https://github.com/dereuromark/upgrade). They both extend the core ones and contain tons of more ideas on how to get code aligned with the current direction of the framework to reduce friction in the long run.
