# Runtime

Unitest WP Copy is not a WordPress installation. It is a selected set of
WordPress functions and classes that can run without a database or full
bootstrap.

## Runtime lifecycle

Runtime state is shared for the life of the PHP process:

1. define any constants required by the test environment;
2. call `\Unitest_WP_Copy\Bootstrap::init()` to load copied WordPress symbols,
   initialize WordPress-like constants, globals, hooks, options, and
   runtime-adapted classes;
3. call `\WP_Mock::bootstrap()` when mocks are needed;
4. configure the globals, hooks, registries, or runtime adapters required by the
   test;
5. run the code under test;
6. restore every changed process-wide value in `tearDown()`.

`Bootstrap::init()` initializes the in-memory option stores. See
[Options](/reference/options) for configuration, lookup, mocking, defaults, and
state cleanup.

For example, tests that register REST routes must remove the cached server:

```php
protected function tearDown(): void {
	unset( $GLOBALS['wp_rest_server'] );
	parent::tearDown();
}
```

## Runtime-adapted classes

Some WordPress classes are reduced to the parts useful in unit tests:

- `\Unitest_WP_Copy\wpdb__Runtime` builds SQL but does not query a database.
- `\Unitest_WP_Copy\WP_REST_Server__Runtime` registers and dispatches routes in memory.

See [Runtime-adapted classes](/reference/runtime-classes) for extension examples,
and `vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md` for their public methods.
