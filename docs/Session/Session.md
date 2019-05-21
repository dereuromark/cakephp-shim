# Session

The CakeSession in 2.x was static, and often times abused in the model layer to make them stateful.
While it is recommended to get the 2.x app model layer stateless prior to upgrading, sometimes this is not easily doable.
If during an upgrade it is necessary to shim this a little while longer, you can use the Session class, which allows
static access from the model layer.
This must be a temporary workaround only, though. Also note that you must invoke the session via regular non-static access prior to using
the static access, so be sure to have at least one `$this->request->getSession()->read(...)` call in your beforeFilter() to enable
session  in your application before any model tries to use the static shim wrapper.

## Session component shim
The session should be used directly via `$this->request->getSession()` object.
But when upgrading you might want to keep the old way until you can refactor it fully:
```php
public $components = ['Shim.Session'];
```
and you don't have to change your code.

## Session helper shim
The session should be used directly via `$this->request->getSession()` object.
But when upgrading you might want to keep the old way until you can refactor it fully:
```php
public $helpers = ['Shim.Session'];
```
and you don't have to change your code.
Note that this Session helper also provides an additional `consume()` method on top.
