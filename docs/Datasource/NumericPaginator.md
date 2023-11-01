# NumericPaginator

`contain` is deprecated/removed in 5.x as per https://book.cakephp.org/5/en/appendices/5-0-migration-guide.html#controller

For those with hundreds of controllers using this option it can be tedious to upgrade all at once.
In that case one can use this one instead:

```php
use Shim\Datasource\Paging\NumericPaginator;

/**
 * @var array<string, mixed>
 */
protected array $paginate = [
    'order' => ['States.modified' => 'DESC'],
    'className' => NumericPaginator::class,
];
```
