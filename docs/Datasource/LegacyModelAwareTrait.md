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
