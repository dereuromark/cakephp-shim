# Shim plugin for CakePHP
[![Build Status](https://api.travis-ci.org/dereuromark/cakephp-shim.svg)](https://travis-ci.org/dereuromark/cakephp-shim)
[![Coverage Status](https://coveralls.io/repos/dereuromark/cakephp-shim/badge.png)](https://coveralls.io/r/dereuromark/cakephp-shim)
[![License](https://poser.pugx.org/dereuromark/cakephp-shim/license.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

Shim plugin to "shim" functionality up and down for CakePHP major versions.
It also provides some small fixes.

## This branch is for shimming 2.x in 3.x
It provides compatibility wrapper access to 2.x functionality in 3.x.

This is mainly useful when upgrading large applications to the next major framework version.
Tons of code needs to be adjusted, using this Shim plugin a few lines less need to be touched.
Especially the ORM layer, which would need heavy refactoring, requires quite a few lines less
of that.

**This plugin requires CakePHP 3.0+.**

## Installation
Please see [SETUP.md](docs/SETUP.md)

## Usage
Please see [Docs](docs)

## Main shims
- Table::find('first') support.
- Table::find('count') support.
- Table::field() support and fieldByConditions() alias to migrate to.
- Still supports model properties `$primaryKey`, `$displayField`, `$order`, `$validate`, `$actsAs` and all
relations (`$belongsTo`, `$hasMany`, ...) as it would be very time-consuming to
manually adjust all those.
- Contains Session component as compatibility wrapper for request session object (and maybe also for Session helper if that one gets deprecated in 3.x).
- Auto-adds Timestamp behavior if `created` or `modified` field exists in table.

## TODO
- Find more useful shims
