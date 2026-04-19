# Test Documentation

## Scope

This document defines test structure and conventions for this repository.
Parser-specific flow is described in [parser.md](parser.md).


## Required Naming

- Test methods must start with `test__`.
  - `my_function` -> `test__my_function`
  - `_my_function` -> `test___my_function`
- Test files must use `__Test.php` suffix, for example `formatting__Test.php`, `WP_Error__Test.php`.
- For class tests, method names must start with `test__` and must not include class name.
  - Use `test__public_methods`, not `test__WP_Error__public_methods`.


## Test Placement

- Core copied function tests: `tests/functions/...`
- Class tests: `tests/classes/...` (one class per file)
- Static-class-method compatibility tests: `tests/classes-statics/...`
- Mock/mockable function tests:
  - source files in `wp-runtime/copy/mockable/...` and `wp-runtime/copy/mocks/...`
  - tests in `tests/mocks/...` with the same WP-path structure


## Coverage Expectations

- Keep tests simple and runtime-focused.
- Basic behavior and non-fatal execution are mandatory.
- If a function/method has branches from parameter variants, cover each branch minimally.
- Each copied function must have its own dedicated test coverage.
- Keep functions isolated in tests; avoid multi-function combined assertions that hide failing symbol behavior.


## Class Independence Rule

For class tests:
- If class is independent in this runtime, add positive smoke coverage for constructor/basic public methods.
- If class has unavoidable dependency on unsupported runtime behavior, add explicit `test__not_independent_*` with `expectException( Error::class )`.


## Mocking and Internal Access

- Do not add ad-hoc WP function stubs directly in tests.
- For mock handler behavior checks, use installed `10up/wp_mock` (`WP_Mock`) only when needed.
- For private/protected member access, prefer closure binding:
  - `Closure::call()`
  - `Closure::bind()`
  - `bindTo( $instance, $scope )`
- Use Reflection only when closure binding is not practical.


## Examples

Property access:
```php
return (array) ( fn() => $this->queue )->call( wp_script_modules() );
```

Method call:
```php
$counter = new Counter();

$call = Closure::bind( fn( $url ) => $this->is_file( $url ), $counter, Counter::class );
$call( 'https://ex.com/file.pdf' );
```
