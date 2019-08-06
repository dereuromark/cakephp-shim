# Year Type class

## YearType
The `YearType` can be used to represent a year in the DB as integer value.

Apart from string form input it can also accept the default Cake datetime control array form.

Setup:

- `Type::map('year', 'Shim\Database\Type\YearType');` in bootstrap
- Manual FormHelper `$this->Form->control('published', ['type' => 'year']);`
