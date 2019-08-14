# Decimal Type class

FC shim (will not be needed in 4.x anymore).

## DecimalType
The `DecimalType` can be used to shim 4.x behavior in 3.x applications.

Main difference what you can expect from 4.x behavior:
- Now uses strings to represent decimal values instead of floats. Using floats can cause loss in precision.

### Setup

- `Type::map('decimal', 'Shim\Database\Type\DecimalType');` in bootstrap

## Alternative
You can also use a value object instead of string handling. Check out [CakeDecimal plugin](https://github.com/dereuromark/cakephp-decimal).
This makes handling decimal values in CakePHP applications even more awesome.
