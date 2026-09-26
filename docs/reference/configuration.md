# Runtime configuration

Bootstrap initializes deterministic WordPress-like constants, globals, and
option values. Tests can replace supported constants before initialization and
can update in-memory option stores afterward.

## Define constants before bootstrap

```php
define( 'ABSPATH', '/srv/wp/' );
define( 'WP_CONTENT_DIR', '/srv/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://wp.test/wp-content' );
define( 'WP_ENVIRONMENT_TYPE', 'development' );
define( 'WP_DEBUG', true );

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

Commonly overridden constants include content and plugin paths, environment and
debug flags, cookie names and paths, memory limits, cron timing, and post
revision settings.

## Configure regular options

```php
$GLOBALS['stub_wp_options']->home             = 'https://example.test';
$GLOBALS['stub_wp_options']->siteurl          = 'https://example.test';
$GLOBALS['stub_wp_options']->my_plugin_option = 'enabled';
```

`get_option()` lookup order is:

1. `pre_option_{$option}` and `pre_option` filters;
2. `$GLOBALS['stub_wp_options']`;
3. a WP_Mock handler when the option is absent from the store;
4. the default-option filter or supplied default value.

## Configure network options

```php
$GLOBALS['stub_wp_site_options']->siteurl = 'https://network.test';
$GLOBALS['stub_wp_site_options']->my_network_option = 'enabled';
```

In multisite mode, `get_site_option()` uses the equivalent site-option lookup
order. Outside multisite it delegates to `get_option()`.

## Restore changed state

Option stores and WordPress globals are shared within the PHP process. Clone or
record values before changing them:

```php
private object $original_options;

protected function setUp(): void {
	parent::setUp();
	\WP_Mock::setUp();
	$this->original_options = clone $GLOBALS['stub_wp_options'];
}

protected function tearDown(): void {
	$GLOBALS['stub_wp_options'] = $this->original_options;
	\WP_Mock::tearDown();
	parent::tearDown();
}
```

The same rule applies to hook registries, REST server state, `$wpdb`, and any
other runtime global changed by a test.
