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
- Mockable handler tests must use the `__mockable_handler` suffix, with two underscores separating the tested function name from the suffix.
  - `my_function` -> `test__my_function__mockable_handler`
  - `_my_function` -> `test___my_function__mockable_handler`


## Test Placement

- Core copied function tests: `tests/functions/...`
- Class tests: `tests/classes/...` (one class per file)
- Static-class-method compatibility tests: `tests/classes-statics/...`
- Mockable function tests:
  - source files in `wp-runtime/copy/mockable/...`
  - tests in `tests/mockable/...` with the same WP-path structure
- Manual mock function tests:
  - source files in `wp-runtime/custom-mocks/...`
  - tests in `tests/custom-mocks/...` with the same WP-path structure


## Coverage Expectations

- Keep tests simple and runtime-focused.
- Keep all behavior checks for one function in a single test method. Do not split branches or input variants into separate test methods.
  Mockable handler behavior remains in its dedicated `__mockable_handler` test method.
- Basic behavior and non-fatal execution are mandatory.
- If a function/method has branches from parameter variants, cover each branch minimally.
- Each copied function must have its own dedicated test coverage.
- Keep functions isolated in tests; avoid multi-function combined assertions that hide failing symbol behavior.


## WordPress Version Compatibility

This library supports multiple WP lines (see `README.md`). Tests run against all of them. The parser skips symbols whose `since` version is higher than the current WP line, so the function simply won't exist in older runtimes.

**Rule**: if a symbol's `since` version in config is higher than the oldest supported WP line (`6.5`), its test **must** include a version-skip guard. Use the symbol's exact `since` version from config:

```php
if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
	$this->markTestSkipped( "wp_js_dataset_name() not exists on WP $wp_ver" );
}
```

Place the guard at the very start of the test method — before any calls to the tested symbol.


## Class Independence Rule

For class tests:
- If class is independent in this runtime, add positive smoke coverage for constructor/basic public methods.
- If class has unavoidable dependency on unsupported runtime behavior, add explicit `test__not_independent_*` with `expectException( Error::class )`.


## Mocking and Internal Access

- Do not add ad-hoc WP function stubs directly in tests.
- For mock handler behavior checks, use installed `10up/wp_mock` (`WP_Mock`) only when needed.
- For private/protected member access, prefer closure binding instead of Reflection:
  - `Closure::call()`
  - `Closure::bind()`
  - `bindTo( $instance, $scope )`

#### Closure Binding Example

Property access:
```php
return (array) ( fn() => $this->queue )->call( wp_script_modules() );
```

Method call:
```php
$call = Closure::bind( fn( $url ) => $this->is_file( $url ), new Counter(), Counter::class );
$call( 'https://ex.com/file.pdf' );
```

Method call (and skip constructor - for methods that no need class state):
```php
$result = Closure::bind(
    fn() => $this->apply_moves_config( $base_config, $mv_config, $wp_line ),
    new ReflectionClass( Config::class )->newInstanceWithoutConstructor(),
    Config::class
)();
```
