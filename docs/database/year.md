# Year Type

## YearType

The `YearType` can be used to represent a year in the database as an integer
value.

Apart from string form input, it can also accept the default datetime control
array form.

Setup:

- Map the type in your bootstrap:

  ```php
  use Cake\Database\Type;

  Type::map('year', 'Shim\Database\Type\YearType');
  ```

- Use it in a manual `FormHelper` control:

  ```php
  echo $this->Form->control('published', ['type' => 'year']);
  ```
