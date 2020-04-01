# Json Type class

FC shim (will not be needed in 4.x anymore).

## JsonType
Does not convert null values to JSON string "null". Respects the nullable part of a table field.
This essentially fixes the internal JSON type handling of the core.

Make sure your db field is default null allowing then if you want it to be optional.
This is mainly for MySQL. Postgres etc can use their native implementations.

Needs:
- `Type::map('json', 'Shim\Database\Type\JsonType');` in bootstrap
- Run `UPDATE table_name SET field_name = null WHERE field_name = 'null';` to clean up the table

You can also use the included shell command to run this for your `json` type fields:
```
bin/cake shim_json [ModelName] -d -v [-p PluginName]
```
Use `-d`/`--dry-run` to analyze your database first.
