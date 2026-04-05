About
=====
This is a helper module for unit testing. It contains copies of WordPress functions and classes that can run in a PHPUnit environment (without loading full WordPress).

It includes copies of original WordPress functions that do not depend on external libraries or database calls, only on PHP.

The main idea behind this module is that when writing unit tests for WordPress, we often have to mock functions that theoretically work purely in PHP. For example:

* `trailingslashit`
* `sanitize_key`
* `is_email`
* `wp_unslash`
* `wp_parse_str`
* `wp_parse_list`
* `wp_normalize_path`
* `get_file_data`
* `maybe_unserialize`
* etc.

WordPress includes many functions and some classes that rely only on PHP. This means we can safely use them in PHPUnit tests without mocking — as they behave the same way they would in production. This helps make the tests more accurate and easier to write.

One issue remains: it’s not always obvious whether a function depends on, say, database data. However, we can be fairly certain that some functions will never rely on the database or other external sources — their logic is purely PHP-based.

This module collects exactly those functions and classes. It simplifies unit testing by reducing the need for mocks and improves reliability by using real function behavior, just like in production.


Usage
-----
To use this module, install it as a Composer package and include its main file in your PHPUnit bootstrap:
```php
// Define the constants required for your tests.
define( 'ABSPATH', 'path/to/wp/' );
define( 'WP_CONTENT_DIR', 'path/to/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://mytest.com/wp-content' );

require_once __DIR__ . '/vendor/doiftrue/unitest-wp-copy/zero.php';

// WP_Mock (if needed - should go after unitest-wp-copy/zero.php)
// WP_Mock::bootstrap();
```

Some core WordPress functions make database calls for options (for example, `get_option('blog_charset')`). In this module, such calls are stubbed via `$GLOBALS['stub_wp_options']->blog_charset`.

Predefined options:

```php
$GLOBALS['stub_wp_options'] = (object) [
	'blog_charset'    => 'UTF-8',
	'timezone_string' => 'UTC',
	'gmt_offset'      => 0,
	'use_smilies'     => true,
	'home'            => 'https://mytest.com',
	'use_balanceTags' => true,
	'WPLANG'          => '',
];
```

You can override these values in your bootstrap file or directly in a test. Example:
```php
$GLOBALS['stub_wp_options']->home = 'https://changed-for-test.com';
```


Additional Info
---------------
The set of functions/classes is defined in `_parser/config-funcs.php` and `_parser/config-classes.php` and can be updated with the `_parser` script, which should be run in a PHP CLI environment.

Before running it, install dependencies with Composer:
```shell
composer install
```
Then run the parser:
```shell
php _parser/run.php
```


Disclaimer
----------
This module is not intended to be a universal solution at this stage. It may contain bugs, and some included functions may have hidden dependencies that prevent them from working correctly in all cases.

The module is still under development and testing, and the current collection of functions is limited. Still, it is already used in at least three projects and is effectively in a beta stage.
