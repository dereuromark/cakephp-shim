# Shim plugin for CakePHP
[![License](https://poser.pugx.org/dereuromark/cakephp-shim/license.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

Shim plugin to "shim" functionality up and down for CakePHP major versions.

## This branch is for backporting 3.x to 2.x
or to shim certain 3.x functionality/features in 2.x.

## Installation
Please see [SETUP.md](/SETUP.md)

## Usage
Please see [Docs](/docs)

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
