# LegacyModelAwareTrait

If your app makes heavy use of `loadModel()` calls, it can be helpful to first
leave them in place when upgrading and take care of further changes later, once the app
is confirmed to run again.

For this you can use this trait on top of the core `M̀odelAwareTrait`:
```php
use Cake\Datasource\ModelAwareTrait;
use Shim\Datasource\LegacyModelAwareTrait;

/**
 * @property \Data\Model\Table\CountriesTable $Countries
 */
#[\AllowDynamicProperties]
class SomeController extends SandboxAppController {

    use ModelAwareTrait;
    use LegacyModelAwareTrait;

	public function someAction() {
		$this->loadModel('Data.Countries');

        $countries = $this->Countries->find()->...;
    }

}
```
It will continue to set the dynamic class property `$Countries` then.

As shown above, it is important to set the `#[\AllowDynamicProperties]` to avoid it blowing up in PHP 8.2+.

Also, the docblock annotation with the actual class is helpful to get full IDE support and auto-complete etc.
This should already be the case usually for your existing code - provided via [IdeHelper plugin](https://github.com/dereuromark/cakephp-ide-helper).

## Setting a custom model class

When setting a custom model class to a controller, the traits cannot be added to this same
controller for inheritance collision if you are using `$modelClass` property.
So it would have to be a parent controller, usually the `AppController` itself.

```php
use Cake\Controller\Controller;
use Cake\Datasource\ModelAwareTrait;
use Shim\Datasource\LegacyModelAwareTrait;

/**
 * @property \Data\Model\Table\CountriesTable $Countries
 */
#[\AllowDynamicProperties]
class AppController extends Controller {

    use ModelAwareTrait;
    use LegacyModelAwareTrait;

}
```
and
```php
use App\Controller\AppController;

/**
 * @property \Data\Model\Table\CountriesTable $Countries
 */
class SomeController extends AppController {

    /**
     * @var string|null
     */
    protected ?string $modelClass = 'Data.Countries';

	public function someAction() {
        $countries = $this->Countries->find()->...;
    }

}
```

In cases where you cannot use the parent class, e.g. directly in some Command or custom class, you can use the
`$defaultModel` property instead:
```php
    /**
     * @var string|null
     */
    protected ?string $defaultModel = 'Packages';
```
It will overwrite any set other property for `$this->loadModel()` calls.

## Autoloading models as before
If you want to completely retain 4.x behavior of autoloading the models, you need to add this snippet
to your (App)Controller::initialize() method, where you added the traits:
```php
    if ($this->modelClass) {
        $this->loadModel();
    }
```
