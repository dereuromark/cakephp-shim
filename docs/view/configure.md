# Configure Helper

For static access you would usually need to include `use` statements in the files
themselves.

You can either replace them with the fully qualified class name, alias the class
in your bootstrap:

```php
class_alias('Cake\Core\Configure', 'Configure');
```

to continue using the 2.x static `Configure` access in your templates, or you can
use the `Configure` helper and replace the static call with a dynamic one:

```php
$this->loadHelper('Shim.Configure');
```

Then you access the `Configure` class this way in any template file directly:

```php
$this->Configure->read($name);
$this->Configure->check($name);
$this->Configure->consume($name);
$this->Configure->readOrFail($name);
```

::: tip
The aliasing approach has the disadvantage that you cannot use another class with
that name. In general, it is usually best to move this logic out of the template —
at least in some later cleanup step.
:::
