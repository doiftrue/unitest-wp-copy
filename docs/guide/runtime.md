# How the runtime works

Unitest WP Copy is a curated compatibility runtime, not a miniature WordPress
installation. The package copies dependency-safe WordPress code and supplies
small adaptations for selected global state and classes.

## Bootstrap order

```php
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

`Bootstrap::init()` loads copied code, default constants, globals, hook
registries, in-memory options, and runtime adapters. WP_Mock starts afterward so
tests can register handlers for supported boundaries.

## Runtime categories

| Category | Purpose |
| --- | --- |
| Copied functions and classes | Original WordPress implementations that are safe in isolation. |
| Mockable copied functions | Original behavior plus an optional WP_Mock handler. |
| Runtime-adapted functions | WordPress-compatible behavior backed by in-memory state. |
| Runtime-adapted classes | Reduced classes that preserve useful WordPress methods without unsupported infrastructure. |

## Options without a database

`get_option()` and `get_site_option()` read object properties instead of database
rows:

```php
$GLOBALS['stub_wp_options']->my_plugin_title = 'Test title';

self::assertSame( 'Test title', get_option( 'my_plugin_title' ) );
```

Stored values have priority over WP_Mock handlers. To mock an option through
WP_Mock, use an option name that is not present in the corresponding store.

```php
\WP_Mock::userFunction( 'get_option', [
	'args'   => [ 'missing_plugin_option', false ],
	'return' => 'mocked value',
] );
```

## Shared process state

The runtime uses the same global state model as WordPress. A mutation remains
visible until the test restores it.

```php
private object $original_options;

protected function setUp(): void {
	parent::setUp();
	\WP_Mock::setUp();

	$this->original_options = clone $GLOBALS['stub_wp_options'];
}

protected function tearDown(): void {
	$GLOBALS['stub_wp_options'] = $this->original_options;
	unset( $GLOBALS['wp_rest_server'] );

	\WP_Mock::tearDown();
	parent::tearDown();
}
```

Restore every global, option, or registry changed by a test. REST tests should
always unset `$GLOBALS['wp_rest_server']` after registering routes.

## Overriding constants and functions

Define supported constants before calling `Bootstrap::init()`:

```php
define( 'WP_CONTENT_DIR', '/srv/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://wp.test/wp-content' );
define( 'WP_ENVIRONMENT_TYPE', 'development' );
```

Copied functions are guarded with `function_exists()`. A project can therefore
define a special-purpose replacement before bootstrap, although WP_Mock is
usually clearer for functions listed as mockable.

## Runtime-adapted classes

Some WordPress classes are useful even though their complete infrastructure is
not. The runtime currently includes adapters such as:

- `\Unitest_WP_Copy\wpdb__Runtime` for SQL-building methods without querying a
  database;
- `\Unitest_WP_Copy\WP_REST_Server__Runtime` for in-memory route registration
  and dispatch.

Bootstrap exposes compatible globals or aliases where required. Consult
`SYMBOLS-INFO.md` for the exact public methods available in each package release.
