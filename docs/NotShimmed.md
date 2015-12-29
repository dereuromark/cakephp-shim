# Things that will be not shimmed

A few things that are breaking changes between 2.x and 3.x are deliberately not shimmed.

## fieldname => scalar|array
In 2.x you could just say `'id' => $id` and $id could be scalar or array, the latter would automatically
transform it into `IS`/`IS NOT`. The reason 3.x does not to this hidden magic anymore is mainly security,
that it can happen by accident, e.g. when passing NULL(s). Forcing `=` unless specially requested to use
the `IN` operator is the reasonable thing here. So shimming that would not be useful.

## Deprecations in 2.x
See the 2.x docs regarding those. But you should have been upgraded your 2.x app already to not use
deprecated functionality anymore before starting the 3.x upgrade.
