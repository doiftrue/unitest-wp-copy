# Options

The runtime provides in-memory implementations of `get_option()` and
`get_site_option()`. They do not use a database.

## Configure options

Set initial values before bootstrap:

```php
$GLOBALS['stub_wp_options'] = (object) [
	'home'      => 'https://example.test',
	'siteurl'   => 'https://example.test',
	'my_option' => 'enabled',
];

$GLOBALS['stub_wp_site_options'] = (object) [
	'my_option' => 'enabled',
];

\Unitest_WP_Copy\Bootstrap::init();
```

Bootstrap keeps provided values and adds any missing defaults.

Change values after bootstrap through the options object:

```php
\Unitest_WP_Copy\Bootstrap::init();

$GLOBALS['stub_wp_options']->home      = 'https://example.test';
$GLOBALS['stub_wp_options']->siteurl   = 'https://example.test';
$GLOBALS['stub_wp_options']->my_option = 'enabled';

$GLOBALS['stub_wp_site_options']->my_option = 'enabled';
```

## How option lookup works

### get_option()

`get_option()` resolves a value in this order:

1. `pre_option_{$option}` and `pre_option` filters;
2. a value from `$GLOBALS['stub_wp_options']`, followed by the
   `option_{$option}` filter;
3. a `WP_Mock` handler, but only when the option is absent from the store;
4. the `default_option_{$option}` filter and the supplied default value.

A pre-option filter short-circuits the remaining lookup when it returns a value
other than `false`.

### get_site_option()

In multisite mode, `get_site_option()` follows the same lookup order, using the
network-option equivalents:

1. `pre_site_option_{$option}` and `pre_site_option` filters;
2. a value from `$GLOBALS['stub_wp_site_options']`, followed by the
   `site_option_{$option}` filter;
3. a `WP_Mock` handler, but only when the option is absent from the store;
4. the `default_site_option_{$option}` filter and the supplied default value.

Outside multisite, `get_site_option()` delegates to `get_option()`.


## Store & Restore option state

Stored values take priority over WP_Mock handlers. This prevents a broad
`get_option()` mock from changing runtime settings used by nested function
calls.

To override a stored option, change `$GLOBALS['stub_wp_options']` or use its
`pre_option_*` or `option_*` filter.

Both stores are process-wide. Save their original values in `setUp()`, set
options directly in the store during a test, and restore the stores in
`tearDown()`:

```php
private object $original_options;
private object $original_site_options;

protected function setUp(): void {
	parent::setUp();

	$this->original_options      = clone $GLOBALS['stub_wp_options'];
	$this->original_site_options = clone $GLOBALS['stub_wp_site_options'];
}

protected function tearDown(): void {
	$GLOBALS['stub_wp_options']      = $this->original_options;
	$GLOBALS['stub_wp_site_options'] = $this->original_site_options;

	parent::tearDown();
}

public function test__plugin_title(): void {
	$GLOBALS['stub_wp_options']->my_plugin_title = 'Test title';

	self::assertSame( 'Test title', get_option( 'my_plugin_title' ) );
}
```

## Mock an option

A WP_Mock handler runs only after global stored values have been checked. Mock
an option name that is absent from `$GLOBALS['stub_wp_options']`:

```php
\WP_Mock::userFunction( 'get_option' )
	->with( 'my_plugin_flag' )
	->andReturn( 'mocked' );

self::assertSame( 'mocked', get_option( 'my_plugin_flag' ) );
```

If `my_plugin_flag` already exists in the store, its stored value is returned
and the handler is not called.

## Default options

Bootstrap adds these values to `$GLOBALS['stub_wp_options']` when they are not
already present:

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

Bootstrap adds these network defaults to `$GLOBALS['stub_wp_site_options']`:

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
