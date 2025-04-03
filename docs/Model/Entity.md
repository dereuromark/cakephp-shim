# Entity shims

Static analyzer happiness included: "PHPStan level 7" approved.
If you are looking into non-entity approaches, consider [DTOs](https://github.com/dereuromark/cakephp-dto).

## Entity read()
You want to read nested properties of your entity, but you do not want tons of !empty() checks?
```php
if ($entity->tags && !empty($entity->tags[2]->name)) {} else {}
```
With modern PHP `?->` can already overcome some of it, but in some cases the read() approach is still better.

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
If you want the opposite, making sure all required fields in the path are present, check the next part(s).

## Entity require()
```php
// in some service method at the beginning
public function buildPdf(Product $product): string {
    $product->require('supplier.company.state.country');

    //Render template
}
```
This allows you to define your required contains (relations) on the entity and otherwise results in a speaking and clear message.

It avoids the usual kind of hidden and not speaking

> warning: 2 :: Attempt to read property "sku" on null

> deprecated: 8192 :: strlen(): Passing null to parameter #1 ($string) of type string is deprecated

> warning: 2 :: foreach() argument must be of type array|object, null given

etc then inside the business logic or rendering when certain relations are expected but not present.

Add the trait and you are all set:
```php
use Shim\Model\Entity\RequireTrait;

class MyEntity extends Entity {

    use RequireTrait;
```

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

## Annotations
If you use the above and want to use the magic methods, make sure to let the [IdeHelper](https://github.com/dereuromark/cakephp-ide-helper) add the annotations on top for them:
```php
    'IdeHelper' => [
        'annotators' => [
            \IdeHelper\Annotator\EntityAnnotator::class => \Shim\Annotator\EntityAnnotator::class,
        ],
    ],
```
This replaces the default one with the Shim version.

## Modified vs dirty

By default, patching as well as manual assigment on the entity often results in more dirty fields than
actually modified ones.
The value might still be the very same, but it is marked as dirty and most likely will be part of the DB
update call.

In some cases it can be useful to know what actually changed, e.g. for auditing and logging purposes.
Here the `ModifiedTrait` comes into play.

NOTE: Since CakePHP 5.2 isDirty() is now handling this the same, so this is only needed prior to that version.

```php
// CakePHP 5.1 or 5.0
$data = ['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'];
$entity = new TestEntity(, ['markClean' => true, 'markNew' => false]);

$entity->set('foo', 'foo');
$entity->set('bar', 'baaaaaar');
$entity->set('foo_bar', 'foo bar');

$result = $entity->getDirty();
$expected = ['foo', 'bar', 'foo_bar'];
$this->assertEquals($expected, $result);

$result  = $entity->isDirty('foo');
$this->assertTrue($result);
$result  = $entity->isModifiedValue('foo');
$this->assertFalse($result);

$result = $entity->getModifiedFields();
$expected = ['bar', 'foo_bar'];
$this->assertEquals($expected, $result);
```
