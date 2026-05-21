# NumericPaginator

`contain` is deprecated and removed in 5.x, as per the
[5.0 migration guide](https://book.cakephp.org/5/en/appendices/5-0-migration-guide.html#controller).

For those with hundreds of controllers using this option, it can be tedious to
upgrade all at once. In that case you can use this paginator instead:

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

::: tip
You can also set this paginator globally for all controllers via the base
[Controller](/controller/) shim and the `Paginator.className` config key.
:::
