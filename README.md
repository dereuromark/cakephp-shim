# Shim plugin for CakePHP
[![License](https://poser.pugx.org/dereuromark/cakephp-shim/license.svg)](https://packagist.org/packages/dereuromark/cakephp-shim)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

Shim plugin to "shim" functionality up and down for CakePHP major versions.
It also provides some small fixes.

## This branch is for backporting 3.x to 2.x
or to shim certain 3.x functionality/features in 2.x.

This is super-useful if you plan on upgrading to the next major framework version
in the (near) future. Using the 3.x shims, you can already use the new functionality and even more
importantly the new syntax.
This way you save yourself quite some time, because that code will need almost zero refactoring when
finally upgrading. So be smart and prepare your 2.x application already for 3.x.
All new code ideally is as close to 3.x as it gets, making the future upgrade as smooth as possible.

**This plugin requires CakePHP 2.5+** (ideally you always use the current stable version).

## Installation
Please see [SETUP.md](/SETUP.md)

## Usage
Please see [Docs](/docs)

## Main shims
- Model::updateAllJoinless() - since 3.x won't join updateAll() anymore.
- Model::deleteAllJoinless() - since 3.x won't join updateAll() anymore.
- IntegrationTestCase (replaces the deprecated ControllerTestCase)
- Shimmed ControllerTestCase (defaults to GET by default) including referrer reset
for WebTestRunner and additional debugging tools.
- Assert query strings (and report usage of deprecated named params) if desired using `Configure::write('App.warnAboutNamedParams', true)`
- Assert contain (and report wrong recursive level) if desired using `Configure::write('App.warnAboutMissingContain', true)`
- Controller::paginate() loading config defaults and providing query string pagination by default.
- Bootstrap configuration for out-of-the-box query string functionality for CakeDC Search plugin.

## Main fixes
- Controller::disableCache() to help to write that directive to the browser for all (even IE).

## TODO
- Backport CakePHP 3.0 core FlashComponent and FlashHelper.

## More shims
can be found in my [Tools plugin](https://github.com/dereuromark/cakephp-tools) directly:

### FlashComponent and FlashHelper
A 3.x branch-off that allows stackable flash messages.

### ModernPasswordHasher
Already use the PHP5.5+ password functionality with the ModernPasswordHasher class and the Passwordable behavior. Easily upgradable to 3.x in minutes then.
That includes auto-conversation (on-the-fly upon login) of old hashs to the new ones via `Fallback` password hasher class.

### RssView
Use RssView (and view-less action) instead of the akward and limited helper approach.

### TestConsoleOutput
TestConsoleOutput() for stdout and stderr instead of mocks. Less fiddling around.

### Refactor controllers
using header monitoring and `Configure::read('App.monitorHeaders')`.

... and [more](https://github.com/dereuromark/cakephp-tools/blob/master/docs/Shims.md)

Also see [these tips](https://github.com/dereuromark/cakephp-upgrade/wiki/Tips-Upgrading-to-CakePHP-2.x).