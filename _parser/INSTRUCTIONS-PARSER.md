Parser Instructions
===================

Collect a list of WordPress PHP functions from the provided file that can be used directly in PHPUnit tests without relying on external libraries or database calls.
Only include functions that can run in a plain PHP environment, so they can be used in PHPUnit without bootstrapping full WordPress.

Also take into account that my PHPUnit environment uses mocks for the following WordPress functions:
- add_action()
- do_action()
- add_filter()
- apply_filters()
- __()
- _e()
- _x()
- _n()
- esc_html__()
- esc_html_e()
- esc_html_x()
- esc_attr__()
- esc_attr_e()
- esc_attr_x()
- esc_html()
- esc_attr()
- esc_url()
- esc_url_raw()
- esc_js()
- esc_textarea()
- And configs from `config-funcs.php` and `config-classes.php`.

I need a list of functions that depend only on PHP and other already available WordPress functions, and do not require external libraries.

Important: if code uses options from `stub_wp_options.php`, then this function/method should be treated as usable in PHPUnit without bootstrapping WordPress, because these calls are stubbed via `$GLOBALS['stub_wp_options']`.

When updating parser configs (`config-funcs.php` / `config-classes.php`):
- If a function/class is not suitable or not used in this project, comment it out.
- Do not delete such entries, so it remains visible that it exists in WordPress.

