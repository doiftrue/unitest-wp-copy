# Constants

Define runtime constants before calling `Bootstrap::init()`:

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

Bootstrap defines its default WordPress-like constants only when they are not
already defined. Constants cannot be changed after bootstrap, so configure all
required values before initializing the runtime.

## Default constants

Bootstrap defines the following constants when they do not already exist.

```php
// Runtime paths and environment.
define( 'ABSPATH', '/path/to/wp/' );
define( 'WPINC', 'wp-includes' );
define( 'WP_CONTENT_DIR', '/path/to/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://wp.test/wp-content' );
define( 'WP_ENVIRONMENT_TYPE', 'local' );
define( 'REST_API_VERSION', '2.0' );

// Deterministic keys and salts for isolated tests.
define( 'AUTH_KEY', 'test-auth-key-unitest-wp-copy' );
define( 'SECURE_AUTH_KEY', 'test-secure-auth-key-unitest-wp-copy' );
define( 'LOGGED_IN_KEY', 'test-logged-in-key-unitest-wp-copy' );
define( 'NONCE_KEY', 'test-nonce-key-unitest-wp-copy' );
define( 'AUTH_SALT', 'test-auth-salt-unitest-wp-copy' );
define( 'SECURE_AUTH_SALT', 'test-secure-auth-salt-unitest-wp-copy' );
define( 'LOGGED_IN_SALT', 'test-logged-in-salt-unitest-wp-copy' );
define( 'NONCE_SALT', 'test-nonce-salt-unitest-wp-copy' );
define( 'SECRET_KEY', 'test-secret-key-unitest-wp-copy' );

// Database result formats used by copied wpdb helpers.
define( 'EZSQL_VERSION', 'WP1.25' );
define( 'OBJECT', 'OBJECT' );
define( 'object', 'OBJECT' );
define( 'OBJECT_K', 'OBJECT_K' );
define( 'ARRAY_A', 'ARRAY_A' );
define( 'ARRAY_N', 'ARRAY_N' );

// Template-part areas.
define( 'WP_TEMPLATE_PART_AREA_HEADER', 'header' );
define( 'WP_TEMPLATE_PART_AREA_FOOTER', 'footer' );
define( 'WP_TEMPLATE_PART_AREA_SIDEBAR', 'sidebar' );
define( 'WP_TEMPLATE_PART_AREA_UNCATEGORIZED', 'uncategorized' );
define( 'WP_TEMPLATE_PART_AREA_NAVIGATION_OVERLAY', 'navigation-overlay' );
```

Constants such as `WP_DEBUG` can still be defined by the test environment, but
they have no runtime default unless listed above.
