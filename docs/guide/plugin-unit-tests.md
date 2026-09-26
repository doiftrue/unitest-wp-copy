# Complete WordPress plugin unit-test setup

This example creates a small plugin and a complete PHPUnit setup. The production
class sanitizes and formats user-provided text with real WordPress functions,
while a site-mode decision is controlled through WP_Mock.

The example targets WordPress 6.9 and PHP 8.1. Change the package constraint to
the WordPress line supported by your plugin.

## Project structure

```text
content-card/
├── composer.json
├── phpunit.xml
├── content-card.php
├── src/
│   └── ContentCard.php
└── tests/
    ├── bootstrap.php
    └── ContentCardTest.php
```

## 1. Composer configuration

Create `composer.json`:

```json
{
  "name": "example/content-card",
  "description": "Example WordPress plugin with isolated unit tests.",
  "type": "wordpress-plugin",
  "require": {
    "php": ">=8.1"
  },
  "require-dev": {
    "doiftrue/unitest-wp-copy": "6.9.*",
    "phpunit/phpunit": "^9.6",
    "10up/wp_mock": "*"
  },
  "autoload": {
    "psr-4": {
      "Example\\ContentCard\\": "src/"
    }
  },
  "scripts": {
    "test": "phpunit"
  },
  "config": {
    "allow-plugins": {
      "composer/installers": true
    }
  }
}
```

Install the test dependencies:

```bash
composer install
```

The runtime package already declares the Composer repository used to resolve its
WordPress-line packages.

## 2. PHPUnit configuration

Create `phpunit.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
	bootstrap="tests/bootstrap.php"
	colors="true"
	cacheResultFile=".phpunit.cache/test-results"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.6/phpunit.xsd"
>
	<testsuites>
		<testsuite name="Content Card">
			<directory suffix="Test.php">tests</directory>
		</testsuite>
	</testsuites>
</phpunit>
```

Add the generated test cache and dependencies to `.gitignore`:

```text
/vendor/
/.phpunit.cache/
```

## 3. Test bootstrap

Create `tests/bootstrap.php`:

```php
<?php

declare(strict_types=1);

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

define( 'WP_ENVIRONMENT_TYPE', 'development' );
define( 'WP_DEBUG', true );

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

Constants must be defined before runtime initialization. Unitest WP Copy must
also initialize before WP_Mock.

## 4. Plugin class

Create `src/ContentCard.php`:

```php
<?php

declare(strict_types=1);

namespace Example\ContentCard;

final class ContentCard {

	public function render( string $title, string $content ): string {
		$title = sanitize_text_field( $title );
		$body  = wpautop( make_clickable( wp_kses_post( $content ) ) );
		$class = is_multisite() ? 'content-card content-card--network' : 'content-card';

		return sprintf(
			'<article class="%s"><h2>%s</h2><div class="content-card__body">%s</div></article>',
			esc_attr( $class ),
			esc_html( $title ),
			$body
		);
	}
}
```

This class uses real WordPress behavior for:

- `sanitize_text_field()`;
- `wp_kses_post()`;
- `make_clickable()`;
- `wpautop()`;
- `esc_attr()` and `esc_html()`.

`is_multisite()` is a runtime boundary that can use its default behavior or a
WP_Mock handler.

## 5. Main plugin file

Create `content-card.php`:

```php
<?php
/**
 * Plugin Name: Content Card
 * Description: Renders sanitized content cards.
 * Requires PHP: 8.1
 * Version: 1.0.0
 */

declare(strict_types=1);

namespace Example\ContentCard;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/autoload.php';

function render_content_card( string $title, string $content ): string {
	return ( new ContentCard() )->render( $title, $content );
}
```

The unit test targets `ContentCard` directly. It does not include the main plugin
file because plugin-header and WordPress lifecycle wiring are not the behavior
under test.

## 6. Unit tests

Create `tests/ContentCardTest.php`:

```php
<?php

declare(strict_types=1);

namespace Example\ContentCard\Tests;

use Example\ContentCard\ContentCard;
use PHPUnit\Framework\TestCase;

final class ContentCardTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test_renders_content_with_real_wordpress_formatting(): void {
		$html = ( new ContentCard() )->render(
			'  <b>Weekly</b> update  ',
			'Visit https://example.com <script>alert(1)</script> <strong>today</strong>'
		);

		self::assertStringContainsString( '<h2>Weekly update</h2>', $html );
		self::assertStringContainsString( '<a href="https://example.com"', $html );
		self::assertStringContainsString( '<strong>today</strong>', $html );
		self::assertStringNotContainsString( '<script>', $html );
		self::assertStringContainsString( 'class="content-card"', $html );
	}

	public function test_adds_network_class_on_multisite(): void {
		\WP_Mock::userFunction( 'is_multisite' )->andReturn( true );

		$html = ( new ContentCard() )->render( 'Network news', 'Shared content' );

		self::assertStringContainsString(
			'class="content-card content-card--network"',
			$html
		);
	}
}
```

The first test is valuable because it does not replace WordPress formatting with
made-up return values. It verifies the behavior that production code actually
depends on. The second test isolates an environment decision by overriding the
mockable `is_multisite()` boundary.

## 7. Run the suite

```bash
composer test
```

Expected result:

```text
OK (2 tests, 7 assertions)
```

## Extending this setup

Before adding another WordPress dependency:

1. Find the function or class in `vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md`.
2. Use its real implementation when the behavior is deterministic.
3. Use `WP_Mock::userFunction()` only when the symbol is listed as mockable.
4. Replace unsupported database, network, or filesystem dependencies with an
   injected project interface or a focused test fake.
5. Restore changed runtime globals and option-store values in `tearDown()`.

For REST route code, continue with [testing REST API code](/guide/rest-api).
