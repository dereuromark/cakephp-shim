## Auth Password Hashing
In 3.x the `Default` password hasher class will be the "default". I backported it as well as other classes to 2.x.
Here is a short overview:

- Shim.Modern (2.x) => Default (3.x)
- Simple (2.x) => Weak (3.x)
- Shim.Fallback => Fallback (3.x)

`sha1`-hashing etc via Simple (alias Weak) should really be avoided in new 2.x+ projects.

### PasswordHasherFactory
Load your hashers the easy way:

```php
App::uses('PasswordHasherFactory', 'Shim.Controller/Component/Auth');

$config = [
	'className' => 'Shim.Fallback',
	'hashers' => [
		'Shim.Modern', 'Simple'
	]
];
$hasher = PasswordHasherFactory::build($config);
```

### ModernPasswordHasher
CakePHP 3.x uses state of the art PHP5.5+ password hashing - which can already work in 5.4 as well thanks to shims.
With this backport you can use it already in 2.x projects:
```
// in your AppController::beforeFilter()
$this->Auth->authenticate = [
	'Form' => [
			'passwordHasher' => 'Shim.Modern'
	]];
```

### FallbackPasswordHasher
If you have a legacy application that still uses sha1 etc, you might want to upgrade smoothly.
You want to have a graceful fallback on old accounts and an auto-hash migration upon login.
Each time a user logs in successfully the new hash replaces the old sha1 one.
Over time all users will be fully migrated and you can switch back to just `Shim.Modern` hasher
directly.

### Putting it all together
In your AppController:
```php
	public $components = ['Auth'];

	public function beforeFilter() {
		parent::beforeFilter();

		$options = [
			'className' => 'Shim.Fallback',
			'hashers' => [
				'Shim.Modern', 'Simple'
			]
		];
		$this->Auth->authenticate = [
			'Form' => [
				'passwordHasher' => $options,
				'fields' => [
					'username' => 'username',
					'password' => 'password'
				],
				'userModel' => '...',
				'scope' => ...,
				...
			]
		];
	}
```
Pro-tip: In case you want username and email to be valid login data, you can also switch Form here with `MultiColumn` from Authorize plugin.
