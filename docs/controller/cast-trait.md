# CastTrait

Inside the controller scope (of MVC) you usually handle request data:

- posted data
- query string

You want to make sure your application gets the right type of data here. If you
pass it along, you want it to match the signature of the method you pass it into.
Especially if you introspect your code with static analyzers (like PHPStan), you
will need this kind of additional type safety. Even more so if you enabled strict
types.

::: tip Static-analyzer friendly
"PHPStan level 7" approved.
:::

Add it at the top of your controller or globally in your AppController:

```php
use Shim\Utility\CastTrait;

class TriggerController extends AppController {

    use CastTrait;
```

The following two type families are available to help keep your controller code
clean.

## Assert

The `assert*()` methods allow null values to be returned, making them "optional"
input casting. Either the right type or null is guaranteed. If the type is
completely wrong (array vs scalar) you even get an exception early on.

```php
public function myAction() {
    // string|null
    $processName = $this->assertString($this->request->getQuery('process'));

    // int|null
    $limit = $this->assertInt($this->request->getQuery('limit'));
}
```

## Cast

The `cast*()` methods will only return the desired type, defaulting falsy/empty
values to the corresponding empty value of that type. If the type is completely
wrong (array vs scalar) you even get an exception early on.

```php
public function myAction() {
    // string
    $processName = $this->castString($this->request->getQuery('process'));

    // bool (also handles string "true"/"false")
    $redirectEnabled = $this->castBool($this->request->getQuery('redirect'));
}
```

## Defaults

Why doesn't the `CastTrait` ship with a second `$default` argument like the core
does in some places?

There are many different "ways" you want your default value to kick in — for
example on `null`, or maybe on any empty value (`''`, `false`, ...).

For that reason PHP offers two different operators to use in ternary form:

```php
// We want any empty input to get overwritten
$processName = $this->castString($this->request->getQuery('process')) ?: 'default';

// We only want null (not 0) to be overwritten
$limit = $this->assertInt($this->request->getQuery('limit')) ?? 100;
```

Just use those where needed to quickly confine your values to non-empty defaults.
This is usually as readable as providing a default argument, and allows you to
customize it more easily.

::: tip
Always have some test cases backing up your asserting/casting.
:::
