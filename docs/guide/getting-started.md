# Getting started

Shim lets you add individual helpers or traits to a CakePHP 5 application. For a
new application, start with a convenience trait. Use legacy shims only for code
you are migrating.

Follow [Installation](/guide/installation) first, then choose a trait or helper
below. No database changes are needed for these examples.

## Read an optional nested value

Add `ReadTrait` to one of your application's entities, for example
`src/Model/Entity/Article.php`:

```php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Shim\Model\Entity\ReadTrait;

class Article extends Entity {
    use ReadTrait;
}
```

In a controller or service:

```php
use App\Model\Entity\Article;

$article = new Article(['title' => 'First article']);
$name = $article->read('author.name', 'Unknown author');
// $name is 'Unknown author' because no author is present.
```

No plugin loading or database changes are needed for this trait. When reading
associated records, fetch the association with `contain()` first. The trait does
not load it for you. See [Entity](/model/entity) for required-value checks.

## Use a plugin helper

The helper works without loading the plugin. If you also want its bootstrap
configuration and legacy time constants, load it:

```bash
bin/cake plugin load Shim
```

In `src/View/AppView.php`, inside `initialize()`:

```php
$this->loadHelper('Shim.Number');
```

Then, in a template:

```php
echo $this->Number->format(null, ['default' => '-']);
// Prints -.
```

Loading Shim runs its bootstrap and enables its own deprecation notices. Use
[Installation](/guide/installation) for details and
[Upgrade Guide](/guide/upgrade) to replace legacy shims as you migrate.
