# Runtime

Unitest WP Copy is not a WordPress installation. It is a selected set of
WordPress functions and classes that can run without a database or full
bootstrap.

## What gets loaded

```php
\Unitest_WP_Copy\Bootstrap::init();
```

This initializes:

- copied WordPress functions and classes;
- WordPress-like constants and globals;
- hooks and in-memory options;
- runtime-adapted classes such as `wpdb` and `WP_REST_Server`.

Call `\WP_Mock::bootstrap()` afterward if tests need mocks.

## Set an option

Options are stored in memory:

```php
$GLOBALS['stub_wp_options']->my_plugin_title = 'Test title';

self::assertSame( 'Test title', get_option( 'my_plugin_title' ) );
```

Network options use `$GLOBALS['stub_wp_site_options']`.

Stored values take priority over WP_Mock handlers. To mock `get_option()`, use an
option name that is absent from the store.

## Restore changed state

Runtime globals are shared for the life of the PHP process:

```php
private object $original_options;

protected function setUp(): void {
	parent::setUp();
	$this->original_options = clone $GLOBALS['stub_wp_options'];
}

protected function tearDown(): void {
	$GLOBALS['stub_wp_options'] = $this->original_options;
	unset( $GLOBALS['wp_rest_server'] );

	parent::tearDown();
}
```

This example assumes the test extends `WP_Mock\Tools\TestCase`. Restore every
global, option, hook, or registry changed by a test before calling
`parent::tearDown()`.

## Override constants

Define constants before bootstrap:

```php
define( 'WP_CONTENT_DIR', '/srv/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://wp.test/wp-content' );
define( 'WP_ENVIRONMENT_TYPE', 'development' );

\Unitest_WP_Copy\Bootstrap::init();
```

## Runtime-adapted classes

Some WordPress classes are reduced to the parts useful in unit tests:

- `\Unitest_WP_Copy\wpdb__Runtime` builds SQL but does not query a database.
- `\Unitest_WP_Copy\WP_REST_Server__Runtime` registers and dispatches routes in memory.

See `vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md` for their public methods.
