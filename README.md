About
=====
Helper library for PHPUnit tests. It provides selected WordPress core functions and classes that can run without full WordPress bootstrap (database or external services).

The goal is to test real WP pure-PHP behavior instead of mocking everything.


Quick Example (Why This Helps)
------------------------------
Suppose your code builds preview URLs like this:

```php
function build_preview_url( string $title ): string {
	return add_query_arg(
		[
			'preview' => '1',
			'slug'    => sanitize_title( $title ),
		],
		'https://example.com/post.php'
	);
}
```

Without this library, you need to mock `sanitize_title()` and `add_query_arg()`, so they do not validate real WordPress behavior.

With this library, the same test can run real implementations in plain PHPUnit:

```php
require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();

$this->assertSame(
	'https://example.com/post.php?preview=1&slug=hello-world',
	build_preview_url( 'Hello World!' )
);
```


Available Symbols
-----------------
For the full list of available classes/functions, see:
[`wp-runtime/SYMBOLS-INFO.md`](wp-runtime/SYMBOLS-INFO.md)



Quick Start
-----------
1. Install a package line that matches your WordPress version line:

```shell
composer require --dev doiftrue/unitest-wp-copy:6.9.*
```

2. Initialize the runtime in your PHPUnit bootstrap:

```php
require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();
```

3. Write unit tests where many WordPress calls do not need mocking.


Supported WordPress Lines
-------------------------
Use the package line that matches your WP version:

| WordPress line | Composer constraint              |
|----------------|----------------------------------|
| 6.5            | `doiftrue/unitest-wp-copy:6.5.*` |
| 6.6            | `doiftrue/unitest-wp-copy:6.6.*` |
| 6.7            | `doiftrue/unitest-wp-copy:6.7.*` |
| 6.8            | `doiftrue/unitest-wp-copy:6.8.*` |
| 6.9            | `doiftrue/unitest-wp-copy:6.9.*` |

Real release tags use 4 numbers, for example `6.9.0.27`:
- `6.9` is the target WordPress version line;
- `0.27` is this repository's release-script version for that line.

In Composer, use:
- `6.9.0.27` - pin one exact release
- `~6.9.0.27` - allow conservative updates starting from this build (usually small runtime fixes)
- `6.9.*` - allow any update in the WP `6.9` line (new copied functions/classes may appear and affect existing tests)


Bootstrap Overrides and Shared State
------------------------------------
Define overrides before `\Unitest_WP_Copy\Bootstrap::init()`.

```php
// tests/bootstrap.php
define( 'ABSPATH', '/srv/wp/' );
define( 'WP_CONTENT_DIR', '/srv/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://tests.example/wp-content' );
define( 'WP_ENVIRONMENT_TYPE', 'development' );
define( 'WP_DEBUG', true );

$GLOBALS['stub_wp_options'] = (object) [
	'home'            => 'https://tests.example',
	'siteurl'         => 'https://tests.example',
	'timezone_string' => 'Europe/Berlin',
	'blog_charset'    => 'UTF-8',
	'gmt_offset' 	  => 2,
	'use_smilies' 	  => false,
	'use_balanceTags' => true,
	'WPLANG' 		  => 'en_US',
];

require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();
```

Runtime globals initialized or updated by bootstrap (shared in one PHP process):
- `$GLOBALS['stub_wp_options']`
- `$GLOBALS['timestart']`
- `$_SERVER['HTTP_HOST']`
- `$blog_id`
- `$wp_plugin_paths`
- `$shortcode_tags`
- `$wp_locale`
- `$wp_post_types`
- `$wp_taxonomies`
- `$wp_filter`
- `$wp_actions`
- `$wp_filters`
- `$wp_current_filter`
- `$allowedposttags`
- `$allowedtags`
- `$allowedentitynames`
- `$allowedxmlentitynames`
- `$wpsmiliestrans`
- `$wp_smiliessearch`

If a test mutates these globals/options, restore them in `setUp()` / `tearDown()`.

Constants you can predefine before bootstrap (WP `6.9` line):

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

Copied functions are wrapped with `if ( ! function_exists( '...' ) )`, so you can override specific functions by defining them before bootstrap init.



Use It When / Do Not Use It When
--------------------------------
Use it when:
- you need real behavior of selected WP functions/classes in plain PHPUnit;
- your tested code mostly depends on WP pure-PHP logic.

Do not use it when:
- you need a full WordPress runtime and bootstrap;
- your test mostly depends on real DB/network/filesystem-heavy WP behavior.


WP_Mock Integration (Optional)
------------------------------
If you need handler-based mocking for supported functions, install WP_Mock:

```shell
composer require --dev 10up/wp_mock
```

Then use WP_Mock per test:

```php
class ExampleTest extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_multisite_mocked() {
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
		$this->assertTrue( is_multisite() );
	}
}
```

For mock-friendly symbols, check:
[`wp-runtime/SYMBOLS-INFO.md`](wp-runtime/SYMBOLS-INFO.md)

See also: https://github.com/10up/wp_mock


Maintainers
-----------
If you maintain this repository, see the docs in [`_docs/`](./_docs/) (runtime, parser, config, tests, release flow).
