# String Type class

FC shim (will not be needed in 4.x anymore).

## StringType
The `StringType` can be used to shim 4.x behavior in 3.x applications.

Main difference what you can expect from 4.x behavior:
- Now marshals array values to `null` instead of an empty string.

### Setup

- `Type::map('string', 'Shim\Database\Type\StringType');` in bootstrap
