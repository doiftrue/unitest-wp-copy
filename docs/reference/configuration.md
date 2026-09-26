# Runtime configuration

Configure constants before bootstrap and options after bootstrap.

## Constants

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

## Options

```php
$GLOBALS['stub_wp_options']->home             = 'https://example.test';
$GLOBALS['stub_wp_options']->siteurl          = 'https://example.test';
$GLOBALS['stub_wp_options']->my_plugin_option = 'enabled';
```

`get_option()` checks:

1. pre-option filters;
2. `$GLOBALS['stub_wp_options']`;
3. a WP_Mock handler;
4. the supplied default value and default-option filter.

## Network options

```php
$GLOBALS['stub_wp_site_options']->my_network_option = 'enabled';
```

In multisite mode, `get_site_option()` follows the same lookup order. Outside
multisite it delegates to `get_option()`.

## Cleanup

Option stores and WordPress globals are process-wide. Save their original values
in `setUp()` and restore them in `tearDown()`.
