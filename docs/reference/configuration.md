# Runtime configuration

Configure constants and initial option values before bootstrap.

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

## Override options

Set initial values before bootstrap:

```php
$GLOBALS['stub_wp_options'] = (object) [
	'home'             => 'https://example.test',
	'siteurl'          => 'https://example.test',
	'my_plugin_option' => 'enabled',
];

\Unitest_WP_Copy\Bootstrap::init();
```

Bootstrap keeps provided values and adds any missing defaults.

Change values after bootstrap through the options object:

```php
\Unitest_WP_Copy\Bootstrap::init();

$GLOBALS['stub_wp_options']->home             = 'https://example.test';
$GLOBALS['stub_wp_options']->siteurl          = 'https://example.test';
$GLOBALS['stub_wp_options']->my_plugin_option = 'enabled';
```

## How `get_option()` works

`get_option()` checks:

1. pre-option filters;
2. `$GLOBALS['stub_wp_options']`;
3. a WP_Mock handler;
4. the supplied default value and default-option filter.

## Override network options

```php
$GLOBALS['stub_wp_site_options'] = (object) [
	'my_network_option' => 'enabled',
];

\Unitest_WP_Copy\Bootstrap::init();
```

In multisite mode, `get_site_option()` follows the same lookup order. Outside
multisite it delegates to `get_option()`.



## Default options

Bootstrap fills `$GLOBALS['stub_wp_options']` with:

```php
[
	'home'                        => 'https://wp.test',
	'siteurl'                     => 'https://wp.test',
	'gmt_offset'                  => 0,
	'timezone_string'             => 'UTC',
	'start_of_week'               => 1,
	'language'                    => 'en-US',
	'blogname'                    => 'Unitest WP Copy',
	'blogdescription'             => 'unitest-wp-copy runtime',
	'blog_public'                 => '1',
	'admin_email'                 => 'admin@wp.test',
	'stylesheet'                  => 'wp-test-stylesheet',
	'template'                    => 'wp-test-template',
	'use_smilies'                 => true,
	'use_balanceTags'             => true,
	'permalink_structure'         => '/%postname%/',
	'show_on_front'               => 'posts',
	'page_on_front'               => 0,
	'page_for_posts'              => 0,
	'site_icon'                   => 0,
	'WPLANG'                      => '',
	'blog_charset'                => 'UTF-8',
	'html_type'                   => 'text/html',
	'thumbnail_size_w'            => 150,
	'thumbnail_size_h'            => 150,
	'thumbnail_crop'              => true,
	'medium_size_w'               => 300,
	'medium_size_h'               => 300,
	'medium_crop'                 => false,
	'medium_large_size_w'         => 768,
	'medium_large_size_h'         => 0,
	'medium_large_crop'           => false,
	'large_size_w'                => 1024,
	'large_size_h'                => 1024,
	'large_crop'                  => false,
	'banned_email_domains'        => [],
	'upload_filetypes'            => 'jpg jpeg png gif',
	'upload_space_check_disabled' => false,
	'fileupload_maxk'             => 1500,
	'registration'                => 'none',
	'blog_upload_space'           => 100,
	'https_migration_required'    => false,
]
```

### Default network options

Bootstrap fills `$GLOBALS['stub_wp_site_options']` with:

```php
[
	'siteurl'                     => $GLOBALS['stub_wp_options']->siteurl,
	'WPLANG'                      => $GLOBALS['stub_wp_options']->WPLANG,
	'banned_email_domains'        => [],
	'upload_filetypes'            => 'jpg jpeg png gif',
	'upload_space_check_disabled' => false,
	'fileupload_maxk'             => 1500,
	'registration'                => 'none',
	'blog_upload_space'           => 100,
]
```


## Cleanup

Option stores and WordPress globals are process-wide. Save their original values
in `setUp()` and restore them in `tearDown()`.
