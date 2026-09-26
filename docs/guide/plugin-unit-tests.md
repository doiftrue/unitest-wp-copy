# Set up unit tests for a WordPress plugin

This is the minimum complete setup needed to run the first isolated unit test
for a WordPress plugin.

It targets WordPress 7.1, PHP 8.1, and PHPUnit 9.6.

## Project structure

```text
content-card/
├── composer.json
├── Makefile
├── phpunit.xml
├── content-card.php          # existing plugin file
├── src/
│   └── ContentCard.php       # existing plugin code
└── tests/
    ├── bootstrap.php
    └── ContentCardTest.php
```

The example assumes the plugin already contains these two files:

`src/ContentCard.php`:

```php
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

`content-card.php`:

```php
/**
 * Plugin Name: Content Card
 */

namespace Example\ContentCard;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/autoload.php';
```

Your plugin can have a different structure and code. The remaining files belong
to the unit-test setup.

## Install the test runtime

Create `composer.json`:

```json
{
	"name": "example/content-card",
	"description": "Example WordPress plugin with isolated unit tests.",
	"type": "wordpress-plugin",
	"scripts": {
		"phpunit": "phpunit"
	},
	"require": {
		"php": ">=8.1"
	},
	"require-dev": {
		"doiftrue/unitest-wp-copy": "7.1.*",
		"phpunit/phpunit": "^9.6",
		"10up/wp_mock": "*"
	}
}
```

## Add the test command

Create `Makefile`:

```makefile
define php_run
    @mkdir -p "$(CURDIR)/tmp/composer-cache"
    docker run --rm $(1) --name UNITEST_WP_COPY__php --user 1000:1000 \
        -v "$(CURDIR):/app" -w /app \
        -v "$(CURDIR)/tmp/composer-cache:/tmp/composer-cache" \
        -e COMPOSER_CACHE_DIR=/tmp/composer-cache \
        composer sh -c "$(2)"
endef

composer: ## Run Composer. Eg: make composer update vendor/package
	$(call php_run,, composer $(filter-out $@,$(MAKECMDGOALS)))

composer.install: ## Install dependencies
	$(call php_run,, composer install $(filter-out $@,$(MAKECMDGOALS)))

composer.update: ## Update dependencies
	$(call php_run,, composer update $(filter-out $@,$(MAKECMDGOALS)))

phpunit: ## Run tests.
	$(call php_run,,composer run phpunit -- --colors=always)
```

Install dependencies:

```bash
make composer.install
```

::: info
Both commands run in the Composer PHP container with the plugin directory mounted at `/app`.
::: 

## Configure PHPUnit

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

Create `.gitignore`:

```text
/vendor/
/.phpunit.cache/
/tmp/
```

## Bootstrap the runtime

Create `tests/bootstrap.php`:

```php
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

define( 'WP_ENVIRONMENT_TYPE', 'development' );
define( 'WP_DEBUG', true );

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

Define runtime constants before `Bootstrap::init()`. Load WP_Mock afterward.

## Write the tests

Create `tests/ContentCardTest.php`:

```php
namespace Example\ContentCard\Tests;

use Example\ContentCard\ContentCard;
use WP_Mock\Tools\TestCase;

final class ContentCardTest extends TestCase {

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

The first test keeps deterministic WordPress behavior real. The second controls
only the environment value needed by the test.

## Run the tests

```bash
make phpunit
```

```text
OK (2 tests, 7 assertions)
```

## Add more WordPress code

Before using another function or class:

1. Find it in `vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md`.
2. Use the real implementation for deterministic behavior.
3. Use WP_Mock only when the function is listed as mockable.
4. Restore changed runtime state in `tearDown()`.
5. Use an integration test for database, filesystem, or full-bootstrap behavior.
