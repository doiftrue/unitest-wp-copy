# Options

The runtime provides in-memory implementations of `get_option()` and
`get_site_option()`. They do not use a database.

## Configure options

Set initial values before or after boot:

```php
use Unitest_WP_Copy\WP_Options;
use Unitest_WP_Copy\WP_Runtime;

WP_Options::set( 'home', 'https://example.test' );
WP_Options::set( 'siteurl', 'https://example.test' );
WP_Options::set_site( 'registration', 'all' );

WP_Runtime::boot();

WP_Options::set( 'my_plugin_title', 'Test title' );
```

Bootstrap keeps provided values and adds any missing defaults.



## How option lookup works

Options should be reed via regular WP functions:

```php
get_option( 'options_name' );
get_site_option( 'options_name' );
```

### get_option()

`get_option()` resolves a value in this order:

1. `pre_option_{$option}` and `pre_option` filters.
2. `WP_Options` store value, followed by the
   `option_{$option}` filter.
3. `WP_Mock` handler, if the option is absent from the store.
4. `default_option_{$option}` filter and the supplied default value.

::: info
A pre-option filter short-circuits the remaining lookup when it returns a value
other than `false`.
::: 

### get_site_option()

The same lookup order, using the network-option equivalents:

1. `pre_site_option_{$option}` and `pre_site_option` filters.
2. `WP_Options` store value, followed by the `site_option_{$option}` filter.
3. `WP_Mock` handler, if the option is absent from the store.
4. `default_site_option_{$option}` filter and the supplied default value.

Outside multisite, `get_site_option()` delegates to `get_option()`.


## Store & Restore option state

Isolate test state. Save and restore both stores with an internal LIFO stack:

```php
protected function setUp(): void {
	parent::setUp();
	WP_Options::save_state();
}

protected function tearDown(): void {
	WP_Options::restore_state();
	parent::tearDown();
}

public function test__plugin_title(): void {
	WP_Options::set( 'my_plugin_title', 'Test title' );

	self::assertSame( 'Test title', get_option( 'my_plugin_title' ) );
}
```

`save_state()` and `restore_state()` require a booted runtime. An unmatched
`restore_state()` throws `LogicException`.

State does not include constants, hooks, `$_SERVER`, or other WP globals.

### Nested state saves

```php
WP_Options::save_state(); // State A.
WP_Options::set( 'blogname', 'B' );

WP_Options::save_state(); // State B.
WP_Options::set( 'blogname', 'C' );

WP_Options::restore_state(); // Restores B.
WP_Options::restore_state(); // Restores A.
```

## Default options

The authoritative default lists live in `WP_Options`. They cover the deterministic
values required by copied runtime functions.

For regular site:

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

For network:

```php
[
	'siteurl'                     => 'https://wp.test',
	'WPLANG'                      => '',
	'banned_email_domains'        => [],
	'upload_filetypes'            => 'jpg jpeg png gif',
	'upload_space_check_disabled' => false,
	'fileupload_maxk'             => 1500,
	'registration'                => 'none',
	'blog_upload_space'           => 100,
]
```
