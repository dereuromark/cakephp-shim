# Bool Type class

FC shim (will not be needed in 4.x anymore).

## BoolType
The `BoolType` can be used to shim 4.x behavior in 3.x applications.

Main difference what you can expect from 4.x behavior:
- No longer marshals non-empty string values to `true` and empty string to `false`. 
  Instead non-boolean string values are converted to `null`.

### Setup

- `Type::map('boolean', 'Shim\Database\Type\BoolType');` in bootstrap
