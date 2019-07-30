# Controller shims

## Referer defaults to local

A security improvement that has been shimmed back from 4.x.:
Local referers are only considered by default.

As soon as you use the Shim Component you activate this and you manually need to disable it, e.g. by setting the 2nd argument to `false`:
```php
$this->referer($url, false);
```

For most of your (internal) redirecting, nothing changes.
For any external or subdomain referring, check what has to be manually whitelisted, and do so carefully.1
