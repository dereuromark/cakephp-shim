# RedirectOutOfBoundsTrait

Often old index links or bots call your paginated views with an outdated, too-high
page number:

```
/events/listing?page=18
```

This results in a 404 `NotFoundException`.

For a real user clicking through, this can be quite the opposite of user-friendly.
Usually you would expect to end on the last possible page instead.

This is what this trait is for. Add it to your AppController and enjoy the
out-of-the-box magic:

```php
use Shim\Controller\Controller;
use Shim\Controller\RedirectOutOfBoundsTrait;

class AppController extends Controller {

    use RedirectOutOfBoundsTrait;

    // ...

}
```

The above example with page 18 will redirect right away to the last page
available, for example 17:

```
/events/listing?page=17
```
