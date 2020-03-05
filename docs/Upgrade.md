# Migration from 3.x to 4.x

## Session
- SessionComponent and SessionHelper now need to be handled through request object.

## Removed shims of 2.x used in 3.x
- Component to assert action name casing.
- Primary level `Table::find('first')` support.
- Primary level `Table::find('count')` support.
- Partial Set class shim
- beforeRender() controller shim for request-data as entity
