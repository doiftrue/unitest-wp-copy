About
=====
Helper library for PHPUnit tests.
It provides selected WordPress core functions/classes that can run without full WordPress bootstrap, database, or external services.

This helps test real WP logic (pure-PHP parts) instead of mocking everything.


Quick Example (Why This Helps)
------------------------------
Suppose your code builds preview URLs using real WordPress helpers:

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

Without this library, tests often mock `sanitize_title()` and `add_query_arg()`, so they do not validate real WordPress behavior.
With this library, the same test can run real implementations in plain PHPUnit:

```php
require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();

$this->assertSame(
	'https://example.com/post.php?preview=1&slug=hello-world',
	build_preview_url( 'Hello World!' )
);
```


Installation
------------
Install as a dev dependency:

```shell
composer require --dev doiftrue/unitest-wp-copy
```

If you want to test WP_Mock handler behavior for mock functions, also install:

```shell
composer require --dev 10up/wp_mock
```


Usage
-----
Add library bootstrap in your PHPUnit bootstrap file:

```php
require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();
```

You can optionally define your own constants:

```php
define( 'ABSPATH', '/path/to/wp/' );
define( 'WP_CONTENT_DIR', '/path/to/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://test.example/wp-content' );

require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();
```

If you do not define them, defaults are used.


Available Symbols Reference
---------------------------
Use [`wp-runtime/SYMBOLS-INFO.md`](wp-runtime/SYMBOLS-INFO.md) as a quick index of all symbols available in this package version.

It includes:

- mock-friendly functions (easy to override in tests);
- functions/classes available as-is.

Check this file first when you need to know whether a specific WP symbol is already available in the test environment.


What bootstrap initializes
--------------------------
- loads copied WP functions/classes and mock implementations;
- defines default WP-like constants if they are not already defined;
- initializes `$GLOBALS['stub_wp_options']`;
- sets `$_SERVER['HTTP_HOST']` from `$GLOBALS['stub_wp_options']->home`;
- initializes base WP globals used by copied code (for example `$wp_locale`, `$shortcode_tags`, `$wp_plugin_paths`, `$timestart`).

This state is shared across tests in the same PHP process.
If a test changes values like `$wp_locale`, `$shortcode_tags`, `$wp_plugin_paths`, `$_SERVER['HTTP_HOST']`, or `$GLOBALS['stub_wp_options']`, restore them in `setUp()`/`tearDown()` to avoid leaking state into other tests.


Stubbed Options
---------------
Some copied functions that normally read options from DB are adapted to read from `$GLOBALS['stub_wp_options']`.

Default values:

```php
$GLOBALS['stub_wp_options'] = (object) [
	'blog_charset'    => 'UTF-8',
	'timezone_string' => 'UTC',
	'gmt_offset'      => 0,
	'use_smilies'     => true,
	'home'            => 'https://unitest-wp-copy.loc',
	'siteurl'         => 'https://unitest-wp-copy.loc',
	'use_balanceTags' => true,
	'WPLANG'          => '',
];
```

Override example in bootstrap or in a test:

```php
$GLOBALS['stub_wp_options']->home = 'https://changed-for-test.com';
```


Mock Functions and WP_Mock
--------------------------
Library also contains mock-friendly WP functions from two sources:

- `wp-runtime/copy/mockable/...`: copies of original WP functions with injected WP_Mock handler support.
- `wp-runtime/copy/mocks/wp-includes/...`: manual mocks where runtime behavior is intentionally adapted for this project.

Examples:

- `is_multisite()`
- `switch_to_blog()`
- `restore_current_blog()`
- i18n helpers like `__()`, `_e()`, `_x()`, `_n()`

These functions have built-in default behavior and can be overridden via WP_Mock handlers:

When you use WP_Mock handlers, call `\WP_Mock::bootstrap()` before loading this package:

```php
require_once __DIR__ . '/vendor/autoload.php';
\WP_Mock::bootstrap();
\Unitest_WP_Copy\Bootstrap::init();
```

```php
\WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
$this->assertTrue( is_multisite() );
```

Overriding specific functions
-----------------------------
Copied functions in this project are wrapped with `if ( ! function_exists( '...' ) )`.
So you can provide your own implementation for specific functions before bootstrap initialization. Example:

```php
// tests/bootstrap.php
require_once __DIR__ . '/wp-overrides.php';
require_once __DIR__ . '/vendor/autoload.php';
\Unitest_WP_Copy\Bootstrap::init();
```

```php
// tests/wp-overrides.php
function is_multisite() {
	return true;
}
```


Limitations
-----------
This is not a full WordPress runtime.

- Only selected functions/classes are included.
- DB/network/filesystem-heavy/full-bootstrap behavior is intentionally out of scope.
- Some edge cases may still require extra project-specific stubs/mocks.
