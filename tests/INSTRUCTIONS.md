For Writing Tests
=================

Tests for WordPress functions. We need the simplest possible test for each function from the list (in the attached file). A basic check that the function works and does not cause fatal errors is enough. It is not necessary to cover all cases. It is important to ensure that for different parameter variants, where logic branches exist, each branch works.

Instructions:
- Each test method must start with the `test__` prefix, followed by the function name. For example, for `my_function`, the method name should be `test__my_function`, and for `_my_function` it should be `test___my_function`.
- Test file names must use the `__Test.php` suffix (double underscore), for example: `formatting__Test.php`, `WP_Error__Test.php`.
- For class tests, method names must start with `test__` but should NOT include the class name in the method name. Example for `WP_Error__Test.php`: use `test__public_methods`, not `test__WP_Error__public_methods`.
- Put class tests in `tests/classes/...` and keep one class per test file.
- For class tests, verify independent runtime behavior in this project environment:
  - If class is independent: add positive smoke tests for constructor/basic public methods.
  - If class has unavoidable external dependency in current project setup: add explicit `test__not_independent_*` test with `expectException( Error::class )` to document this limitation.
- Tests should be simple - no need to fully cover function/method logic we only need to test that all logics of function works without WordPress environment.
- Each function must have its own separate test. Do not combine multiple functions into a single test.
- Never create WP function mocks to pass the test - we need to test that function can work without WP environment, but inside this project WP-Copy environment.

## Test example
There is a function:
```php
function add_numbers( $a, $b = 0 ) {
	if( $b ) {
		return $a + $b;
	}
	return $a;
}
```

Test file:
```php
use PHPUnit\Framework\TestCase;

class AddNumbersTest extends TestCase {
	
	public function test__add_with_two_parameters() {
		$this->assertEquals( 4, add_numbers( 4 ) );
		$this->assertEquals( 5, add_numbers( 2, 3 ) );
	}
	
}
```
