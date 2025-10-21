About
=====
This is a helper module for UNIT testing, which contains copies of WP functions and classes that can be run in a PHPUnit environment (without WordPress). Here are collected copies of original WordPress functions that not depends on any external libriray or DB calls - only PHP.

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
To use this module, you need to install it as a Composer package and include its main file in your PHPUnit bootstrap using the following line of code:
```php
// Define necessary for you tests constants
define( 'ABSPATH', 'path/to/wp/' );
define( 'WP_CONTENT_DIR', 'path/to/wp/wp-content' );
define( 'WP_CONTENT_URL', 'https://mytest.com/wp-content' );

require_once __DIR__ . '/vendor/doiftrue/unitest-wp-copy/zero.php';

// WP_Mock (if needed - should go after unitest-wp-copy/zero.php)
// WP_Mock::bootstrap();
```

Some core WordPress functions make DB calls for options (for example, `get_option('blog_charset')`). Such calls are stubbed by the following construct: `$GLOBALS['stub_wp_options']->blog_charset`. The list of such predefined options:

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

You can override them in your bootstrap file, or directly in the test. Example of overriding the `home` option in the bootstrap file:
```php
$GLOBALS['stub_wp_options']->home = 'https://changed-for-test.com';
```


Additional Info
---------------
The set of functions/classes is specified in the config file `_parser/config-funcs.php` & `_parser/config-classes.php` and updated with ``_parser`` script that should be run under php-cli environment. Before run you need to install dependencies using composer:
```shell
composer install
```
After that, you can run the parser:
```shell
php _parser/run.php
```


Disclaimer
----------
This module is not intended to be a universal solution at this stage, and it may contain bugs or include functions that, in some cases, have hidden dependencies and might not work entirely correctly. The module is still under development and testing. The collection of functions is currently quite limited. Nevertheless, I’m already using it in at least three projects, and it’s currently in a kind of beta stage.

