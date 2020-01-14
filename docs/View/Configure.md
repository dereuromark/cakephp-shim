# Configure helper shim
For static access you would usually need to include use statements in the files itself.

You can either replace them with the FQCN, use
```php
class_alias('Cake\Core\Configure', 'Configure');
```
in your bootstrap to continue to use the 2.x static Configure access in your templates, or you can
use the `Configure` helper and preg replace the static with a dynamic call:
```php
$this->loadHelper('Shim.Configure');
```
Then you access the Configure class this way in any template file directly:
```php
$this->Configure->read($name)
$this->Configure->check($name)
$this->Configure->consume($name)
$this->Configure->readOrFail($name)
```
The aliasing has the disadvantage that you cannot use another class with that name.
In general it is usually best to move the logic here out of the template, though. At least in some later cleanup step then.
