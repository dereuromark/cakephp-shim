# TestSuite

Shim provides test base classes, captured console output, and a utility trait.
Use the PHPUnit version allowed by the repository's Composer requirements.

## TestTrait

Add `Shim\TestSuite\TestTrait` to a test class, or extend
`Shim\TestSuite\TestCase` or `Shim\TestSuite\IntegrationTestCase`.

- `invokeMethod($object, $name, $arguments)` invokes a protected or private method.
- `invokeProperty($object, $name)` reads a protected or private property; it returns
  `null` when the property does not exist.
- `osFix($string)` normalizes line endings to `\n`.
- `isDebug()` detects `--debug` in the process arguments.
- `isVerbose()` detects `-v` or `-vv`; `isVerbose(true)` detects only `-vv`.
- `debug($value)` prints through CakePHP's `debug()` only in verbose mode.

Current PHPUnit versions do not accept `-v` or `-vv`. To use Shim's verbose
helpers, set the flag in a specific test and restore the process arguments:

```php
$originalArgv = $_SERVER['argv'];
try {
    $_SERVER['argv'][] = '-v';
    $this->debug($result);
} finally {
    $_SERVER['argv'] = $originalArgv;
}
```

For normal PHPUnit diagnostics, use supported options:

```bash
vendor/bin/phpunit --filter testExample --debug
```

`--debug` enables `isDebug()`, but does not enable Shim's verbose output.

## IntegrationTestCase

To let exceptions reach your test rather than render an error response, set:

```php
protected bool $disableErrorHandlerMiddleware = true;
```

This applies when the application middleware queue is built. Use it for requests
where you want exceptions to fail the test.

## ConsoleOutput

`Shim\TestSuite\ConsoleOutput` captures writes. Create separate instances for
standard output and error output:

```php
use Cake\Console\ConsoleIo;
use Shim\TestSuite\ConsoleOutput;

$out = new ConsoleOutput();
$err = new ConsoleOutput();
$io = new ConsoleIo($out, $err);
$io->out('Finished');

$this->assertSame('Finished' . PHP_EOL, $out->output());
$this->assertSame('', $err->output());
```

Pass `ConsoleIo` to your command's `execute()` method when testing a command
directly. For application command integration tests, use CakePHP's
`ConsoleIntegrationTestTrait` and its output assertions.
