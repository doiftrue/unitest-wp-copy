---
layout: home

hero:
  name: Unitest WP Copy
  text: Real WordPress behavior in isolated PHPUnit tests
  tagline: Use selected WordPress core functions and classes without a database, HTTP server, or full WordPress bootstrap.
  image:
    src: /logo.svg
    alt: Unitest WP Copy logo
  actions:
    - theme: brand
      text: Get started
      link: /guide/getting-started
    - theme: alt
      text: Complete plugin example
      link: /guide/plugin-unit-tests

features:
  - title: Real core logic
    details: Exercise selected WordPress formatting, sanitization, URL, REST, and utility code instead of replacing everything with stubs.
  - title: Fast and isolated
    details: Run ordinary PHPUnit tests without installing WordPress, connecting a database, or booting the request lifecycle.
  - title: Mockable boundaries
    details: Combine the runtime with WP_Mock when environment-dependent WordPress functions need controlled results.
---

## Start with the matching WordPress line

```bash
composer require --dev doiftrue/unitest-wp-copy:6.9.* 10up/wp_mock
```

Initialize Unitest WP Copy before WP_Mock in the PHPUnit bootstrap:

```php
require_once __DIR__ . '/../vendor/autoload.php';

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

Continue with the [getting-started guide](/guide/getting-started), or copy the
complete [WordPress plugin test setup](/guide/plugin-unit-tests).
