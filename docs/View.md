## View

### JsonView
The JsonShimView aims to backport 3.x to 2.x.
In 3.x there will be 4 flags set as default (as they require PHP 5.3+): `JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT`.
This is to comply with RFC4627.

Use this class and those will be then already set by default for your new 2.x app, as well.
This eases migration of 2.x to 3.x, since the output will not suddenly change.

#### Passing custom options
You can pass custom options easily (available since 2.6.5, but with this shim class already for any 2.x version!):
```php
// We want JSON_PRETTY_PRINT to always be on (not just in debug mode):
$options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRETTY_PRINT;
$this->set('_jsonOptions', $options);
```

Note: Passing `false` will get you the former 2.x behavior again.

#### DRY Configure options
You can also declare your options side-wide using Configure key 'Shim.jsonOptions'.
