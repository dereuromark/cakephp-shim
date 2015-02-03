## Model
... using ShimModel class.

# get()
This shims the 3.x pendant to 2.x.

Former way:
```php
App::uses('AppController', 'Controller');

class PostsController extends AppController {
    
    /**
     * @throws NotFoundException If record cannot be found.
     */
    public function view($id = null) {
        $record = $this->Post->findById($id);
        if (!$record) {
            throw new NotFoundException();
        }
        ...
    }
    
}
```

New way:
```php
App::uses('AppController', 'Controller');

class PostsController extends AppController {
    
    /**
     * @throws RecordNotFoundException If record cannot be found.
     */
    public function view($id = null) {
        $record = $this->Post->get($id);
        ...
    }
}
```
