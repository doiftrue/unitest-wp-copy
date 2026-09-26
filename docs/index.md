---
layout: home

hero:
  name: Unitest WP Copy
  text: Test WordPress code without loading WordPress
  tagline: Real WordPress functions and classes in fast, isolated PHPUnit tests.
  image:
    src: /logo.svg
    alt: Unitest WP Copy logo
  actions:
    - theme: brand
      text: Start here
      link: /guide/getting-started
    - theme: alt
      text: Set up plugin tests
      link: /guide/full-unit-test-setup

features:
  - title: Real WordPress behavior
    details: Use selected core formatting, sanitization, URL, hook, REST, and utility code.
  - title: No WordPress installation
    details: No database, web server, or full WordPress bootstrap is required.
  - title: Works with WP_Mock
    details: Mock supported environment boundaries while keeping deterministic core logic real.
---

## Install

Choose the package line that matches your WordPress version:

```bash
composer require --dev doiftrue/unitest-wp-copy:7.1.* 10up/wp_mock
```

Initialize the runtime before WP_Mock:

```php
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```
