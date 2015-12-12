## Routing

### Deprecate old routing
With Configure `Shim.warnAboutOldRouting` you can make the URL arrays 3.x compatible already and warn about old usages.

### Routing Speedup
With the `Config/routes.speedup.php` file content you can speed up your 2.6+ routing.
It will pre-compile and cache your routes for quicker re-use on each page load for you.

See the explanations inside this file on how to use.

#### Limitations
There is some limitations on this approach:
- If you have any conditional check it will not work. The resultant is the routes with the conditional matching for the time where the cache was written.
- If you generate routes dynamically (ie, from database) it also cause problems. The routes are going to be generated at the time you create the cache,
but if you add new routes on database it will not be available.
- If you have dynamic load for plugins, plugins update, etc it can be a problem as well because the cache is based on the sha1 of the routes.php on the app.
- When running through console, there might also be some tricky things to be done.

You might be able to still leverage it by using different cache keys or otherwise slightly modifying the script, though.

#### Real life examples
Performance analysis on some real life projects to come soon as benchmark..

#### Credits
Credits to [jrbasso](https://github.com/jrbasso) for the ground works on this little snippet.
