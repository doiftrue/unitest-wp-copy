# Start here

Unitest WP Copy runs selected WordPress core code inside ordinary PHPUnit tests.

## Install the runtime

Use the package line that matches the WordPress version supported by your plugin:

```bash
composer require --dev  doiftrue/unitest-wp-copy:7.1.*  10up/wp_mock
```

For example, `7.1.*` contains code copied from WordPress 7.1.

## Add the test bootstrap

Create `tests/bootstrap.php`:

```php
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

::: warning
The order matters: Unitest WP Copy defines the WordPress functions, then WP_Mock
adds mock handlers for supported functions.
:::

## Write a test

```php
use WP_Mock\Tools\TestCase;

final class CommentTest extends TestCase {

	public function test_renders_safe_html(): void {
		$html = wpautop(
			make_clickable(
				wp_kses_post(
					'Visit https://example.com <script>alert(1)</script> <b>today</b>'
				)
			)
		);

		self::assertStringNotContainsString( '<script>', $html );
		self::assertStringContainsString( '<a href="https://example.com"', $html );
		self::assertStringContainsString( '<b>today</b>', $html );
	}
}
```

This test runs the real WordPress implementations of `wp_kses_post()`,
`make_clickable()`, and `wpautop()`. The WP_Mock test case handles setup and
cleanup automatically.

## Mock a WordPress boundary

Some runtime functions can be controlled through WP_Mock:

```php
\WP_Mock::userFunction( 'is_multisite' )->andReturn( true );

self::assertTrue( is_multisite() );
```

Only functions listed as **mockable** support this. See
[`SYMBOLS-INFO.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/SYMBOLS-INFO.md).

## Check available functions and classes

Open [`SYMBOLS-INFO.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/SYMBOLS-INFO.md):

```text
vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md
```

This file matches the installed runtime version and is the authoritative symbol
list.
