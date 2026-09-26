# Getting started

Unitest WP Copy provides selected WordPress core functions and classes that can
run in plain PHPUnit. It is intended for code that needs real WordPress
pure-PHP behavior but not a live database, network, filesystem, or complete
WordPress request lifecycle.

## Requirements

- PHP supported by the selected package release.
- PHPUnit.
- A package version matching the WordPress line used by the project.
- WP_Mock when tests need to replace supported WordPress boundaries.

## Installation

Install the package line matching the target WordPress version:

```bash
composer require --dev doiftrue/unitest-wp-copy:6.9.* 10up/wp_mock
```

Release tags contain four numbers. For example, `6.9.2.8` targets WordPress 6.9,
while `2.8` is the Unitest WP Copy release within that line.

| Constraint | Meaning |
| --- | --- |
| `6.9.2.8` | Use one exact runtime release. |
| `~6.9.2.8` | Allow conservative updates from that release. |
| `6.9.*` | Allow every Unitest WP Copy release for WordPress 6.9. |

::: warning Match the WordPress line
Do not use a `6.9.*` runtime to represent a plugin that targets WordPress 6.8.
Copied symbols and behavior can differ between WordPress lines.
:::

## PHPUnit bootstrap

Create `tests/bootstrap.php`:

```php
<?php

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

Unitest WP Copy must initialize first. It loads real runtime functions; WP_Mock
then installs handlers that can override functions explicitly marked as
mockable.

## First test

```php
<?php

use PHPUnit\Framework\TestCase;

final class FormattingTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test_formats_safe_comment_html(): void {
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

The test executes the copied WordPress implementations of `wp_kses_post()`,
`make_clickable()`, and `wpautop()`.

## Check a symbol before using it

The package intentionally contains only a selected subset of WordPress. Check
[`SYMBOLS-INFO.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/SYMBOLS-INFO.md)
before depending on a function or class.

The file separates:

- runtime-adapted classes;
- manually adapted functions;
- copied functions that WP_Mock can override;
- regular copied functions and classes.

## Choose real behavior or a mock

Use real runtime behavior for deterministic operations such as formatting,
sanitization, parsing, and value transformations. Mock an environment boundary
when the test needs a specific state:

```php
\WP_Mock::userFunction( 'is_multisite' )->andReturn( true );

self::assertTrue( is_multisite() );
```

Only functions listed as mockable can be overridden after runtime bootstrap.

::: info Next step
Read [how the runtime works](/guide/runtime), or use the complete
[plugin unit-test setup](/guide/plugin-unit-tests).
:::
