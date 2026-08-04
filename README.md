About
=====
Helper library for PHPUnit tests. It provides selected WordPress core functions and classes that can run without full WordPress bootstrap (database or external services).

Use it with [WP_Mock](https://github.com/10up/wp_mock). The runtime keeps real WordPress pure-PHP behavior, while WP_Mock lets tests replace functions marked as mockable when that is needed — which is almost always the case in unit tests.


Quick Start
-----------
1. Install the package line matching your WordPress version, plus WP_Mock:

	```shell
	composer require --dev doiftrue/unitest-wp-copy:6.9.* 
	composer require --dev 10up/wp_mock
	```

2. Initialize both in the PHPUnit bootstrap. Unitest_WP_Copy must initialize first:

	File: `tests/bootstrap.php`
	```php
	require_once __DIR__ . '/../vendor/autoload.php';

	\Unitest_WP_Copy\Bootstrap::init();
	\WP_Mock::bootstrap();
	```


Quick Example
-------------
Suppose your code turns a raw, user-submitted comment into safe HTML:

```php
function render_comment( string $raw ): string {
	// wp_kses_post() strips disallowed tags.
	// make_clickable() linkifies URLs.
	// wpautop() adds paragraphs — all real WordPress logic.
	return wpautop( make_clickable( wp_kses_post( $raw ) ) );
}
```

The test uses real WordPress sanitizing and formatting behavior, but can still mock a supported boundary when necessary:

```php
class RenderCommentTest extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();
	}

	protected function tearDown(): void {
		WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__renders_safe_html(): void {
		$html = render_comment(
			'Great post! <script>alert(1)</script> visit https://example.com <b>thanks</b>'
		);

		$this->assertStringNotContainsString( '<script>', $html );                   // kses removed it
		$this->assertStringContainsString( '<a href="https://example.com"', $html ); // linkified
		$this->assertStringContainsString( '<b>thanks</b>', $html );                 // allowed tag kept
	}

	public function test__mocks_a_supported_function(): void {
		WP_Mock::userFunction( 'is_multisite' )->andReturn( true );
		$this->assertTrue( is_multisite() );
	}
}
```


Without WP_Mock
---------------
You may initialize only `\Unitest_WP_Copy\Bootstrap::init()` and use the real runtime. However, you will not be able to conveniently mock functions that the runtime has already loaded.


Available Symbols
-----------------
For the full list of available classes/functions, see:
[`SYMBOLS-INFO.md`](SYMBOLS-INFO.md). It separately lists symbols that are mockable via WP_Mock.

### Runtime-Adapted Classes

Some WordPress classes cannot be copied as a whole, so the runtime provides a partial adapter instead. Such classes are listed in the first section of [`SYMBOLS-INFO.md`](SYMBOLS-INFO.md) together with their public methods and properties, where `[wp]` marks an unchanged copied WordPress method and `[adapted]` marks a runtime-specific implementation.

They are regular PHP classes, not WP_Mock symbols: use an instance directly, or extend it to build your own mock.

Currently available: `\Unitest_WP_Copy\WPDB_Runtime` — a non-querying `wpdb` adapter for SQL-building code. Bootstrap assigns an instance to the `$wpdb` global.

```php
global $wpdb;

$query = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_title = %s", "O'Reilly" );
$this->assertSame(
	"SELECT * FROM wp_posts WHERE post_title = 'O\\'Reilly'",
	$wpdb->remove_placeholder_escape( $query )
);
```

Extend it when your code needs querying methods:

```php
class My_WPDB extends \Unitest_WP_Copy\WPDB_Runtime {

	public array $results = [];

	public function get_results( $query = null, $output = OBJECT ) {
		return $this->results;
	}
}

$GLOBALS['wpdb'] = new My_WPDB();
```

Restore `$GLOBALS['wpdb']` in `tearDown()` if a test replaces it.

Supported WordPress Lines
-------------------------
Use the package line that matches your WP version:

| WordPress line | Composer constraint              |
|----------------|----------------------------------|
| 7.0            | `doiftrue/unitest-wp-copy:7.0.*` |
| 6.9            | `doiftrue/unitest-wp-copy:6.9.*` |
| 6.8            | `doiftrue/unitest-wp-copy:6.8.*` |
| 6.7            | `doiftrue/unitest-wp-copy:6.7.*` |
| 6.6            | `doiftrue/unitest-wp-copy:6.6.*` |
| 6.5            | `doiftrue/unitest-wp-copy:6.5.*` |

Real release tags use 4 numbers, for example `7.0.2.8`:
- `7.0` is the target WordPress version line;
- `2.8` is this repository's version for that line.

Usage examples in your composer.json:
- `7.0.2.8` - pin one exact release.
- `~7.0.2.8` - allow conservative updates starting from this build (usually small runtime fixes).
- `7.0.*` - allow any update in the WP `7.0` line (new copied functions/classes may appear and affect existing tests).


Bootstrap Overrides and Shared State
------------------------------------
Define overrides before `\Unitest_WP_Copy\Bootstrap::init()`.

```php
// tests/bootstrap.php
define( 'ABSPATH', '/srv/wp/' );
define( 'WP_CONTENT_DIR', '/srv/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://wp.test/wp-content' );
define( 'WP_ENVIRONMENT_TYPE', 'development' );
define( 'WP_DEBUG', true );

// Used by get_option()
$GLOBALS['stub_wp_options'] = (object) [
	'home'                => 'https://wp.test',
	'siteurl'             => 'https://wp.test',
	'gmt_offset'          => 0,
	'timezone_string'     => 'UTC',
	'language'            => 'en-US',
	'blogdescription'     => 'unitest-wp-copy runtime',
	'admin_email'         => 'admin@wp.test',
	'stylesheet'          => 'unitest-wp-copy',
	'use_smilies'         => true,
	'use_balanceTags'     => true,
	'WPLANG'              => '',
	'blog_charset'        => 'UTF-8',
	'html_type'           => 'text/html',
	'thumbnail_size_w'    => 150,
	'thumbnail_size_h'    => 150,
	'thumbnail_crop'      => true,
	'medium_size_w'       => 300,
	'medium_size_h'       => 300,
	'medium_large_size_w' => 768,
	'medium_large_size_h' => 0,
	'large_size_w'        => 1024,
	'large_size_h'        => 1024,
];

// Used by get_site_option()
$GLOBALS['stub_wp_site_options'] = (object) [
	'site_name' => 'Test network',
];

require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

### Redefine Runtime Globals

Runtime globals initialized or updated by bootstrap (shared in one PHP process):
```php
$GLOBALS['stub_wp_options']
$GLOBALS['stub_wp_site_options']
$GLOBALS['timestart']
$_SERVER['HTTP_HOST']
$blog_id
$wp_plugin_paths
$shortcode_tags
$wp_locale
$wp_post_types
$wp_taxonomies
$wp_filter
$wp_actions
$wp_filters
$wp_current_filter
$allowedposttags
$allowedtags
$allowedentitynames
$allowedxmlentitynames
$wpsmiliestrans
$wp_smiliessearch
```

If a test mutates these globals/options, restore them in `setUp()` / `tearDown()`.

### How `get_option()` Works

`get_option()` uses `$GLOBALS['stub_wp_options']` instead of a database. Configured options have priority over `WP_Mock` handlers so that a broad mock cannot accidentally change options used by nested runtime calls.

The lookup order is:

1. `pre_option_{$option}` and `pre_option` filters;
2. the value in `$GLOBALS['stub_wp_options']` and the `option_{$option}` filter;
3. a `WP_Mock::userFunction( 'get_option', ... )` handler for an option not present in the store;
4. the `default_option_{$option}` filter and the default value.

Override a configured option by changing the store:
```php
$GLOBALS['stub_wp_options']->medium_size_w = 640;
```

Use `WP_Mock` to mock an option that does not exist in `$GLOBALS['stub_wp_options']`:
```php
WP_Mock::userFunction( 'get_option', [
	'args'   => [ 'my_plugin_option', false ],
	'return' => 'test-value',
] );
```
IMPORTANT: `WP_Mock` cannot override an option when it exists in `$GLOBALS['stub_wp_options']`.


### Redefine Constants

Constants you can predefine before bootstrap:

```txt
ABSPATH
WPINC
WP_CONTENT_DIR
WP_CONTENT_URL
WP_ENVIRONMENT_TYPE
WP_START_TIMESTAMP
WP_MEMORY_LIMIT
WP_MAX_MEMORY_LIMIT
WP_DEVELOPMENT_MODE
WP_DEBUG
WP_DEBUG_DISPLAY
WP_DEBUG_LOG
WP_CACHE
SCRIPT_DEBUG
MEDIA_TRASH
SHORTINIT
WP_PLUGIN_DIR
WP_PLUGIN_URL
PLUGINDIR
WPMU_PLUGIN_DIR
WPMU_PLUGIN_URL
MUPLUGINDIR
COOKIEHASH
USER_COOKIE
PASS_COOKIE
AUTH_COOKIE
SECURE_AUTH_COOKIE
LOGGED_IN_COOKIE
TEST_COOKIE
COOKIEPATH
SITECOOKIEPATH
ADMIN_COOKIE_PATH
PLUGINS_COOKIE_PATH
COOKIE_DOMAIN
RECOVERY_MODE_COOKIE
FORCE_SSL_ADMIN
AUTOSAVE_INTERVAL
EMPTY_TRASH_DAYS
WP_POST_REVISIONS
WP_CRON_LOCK_TIMEOUT
CUSTOM_TAGS
```

### Redefine Functions

Copied functions are wrapped with `if ( ! function_exists( '...' ) )`, so you can override specific functions by defining them before bootstrap init.



When to Use It
--------------
Use it when:
- you need real behavior of selected WP functions/classes in plain PHPUnit;
- your tested code mostly depends on WP pure-PHP logic.

Do not use it when:
- you need a full WordPress runtime and bootstrap;
- your test mostly depends on real DB/network/filesystem-heavy WP behavior.



Instructions for AI Agents
--------------------------
Add the following to the testing section of your project's `AGENTS.md`:

```md
### Tests Runtime

This project uses `doiftrue/unitest-wp-copy` with `WP_Mock` for PHPUnit tests.

Before writing or changing tests:

1. Read `vendor/doiftrue/unitest-wp-copy/README.md` to understand the test runtime.
2. Check `vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md` for the WordPress functions and classes available in the runtime. Its first section lists runtime-adapted classes (like `\Unitest_WP_Copy\WPDB_Runtime`) with their public methods — use or extend them instead of WP_Mock.
3. Use `WP_Mock` when a runtime function listed as mockable needs to be mocked.
```
