# Entity shims

Static analyzer happiness included: "PHPStan level 7" approved.
If you are looking into non-entity approaches, consider [DTOs](https://github.com/dereuromark/cakephp-dto).

## Entity read()
You want to read nested properties of your entity, but you do not want tons of !empty() checks?
```
if (!empty($entity->tags && !empty($entity->tags[2]->name)) {} else {}
```

Add the trait first:
```php
use Shim\Model\Entity\ReadTrait;

class MyEntity extends Entity {

    use ReadTrait;
```

Then you can use it like this:
```php
echo $entity->read('tags.2.name', $default);
```

This means, you are OK with part of the path being empty/null.
If you want the opposite, making sure all required fields in the path are present, check the next part about getOrFail().

## Entity get...OrFail()/set...OrFail()
You want to use "asserted return/param values" or "safe chaining" in your entities?
Then you want to ensure you are not getting null values returned where you expect actual values.

Add the trait first:
```php
use Shim\Model\Entity\GetSetTrait;

class MyEntity extends Entity {

    use GetSetTrait;
```
You can also use the GetTrait/SetTrait separately if you don't need both get/set functionality.

Now in code you can use
```php
$entity->getOrFail('field'); // Cannot be null
$entity->getFieldOrFail(); // Cannot be null

$entity->setOrFail('field', $value); // Cannot be null
$entity->setFieldOrFail($value); // Cannot be null
```

Use the included annotator to get all method annotations into your entities:
```php
'IdeHelper' => [
    'annotators' => [
        \IdeHelper\Annotator\EntityAnnotator::class => \Shim\Annotator\EntityAnnotator::class,
    ],
```
This replaces the native one and adds support for these get methods on top.

Also PHPStan now can help you in more detail, e.g.

> Parameter #1 $value of method App\Model\Entity\User::setActiveOrFail() expects bool, null given.
