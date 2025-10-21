GPT Prompt for uploaded file
============================

Collect a list of WordPress PHP functions from the provided file that can be used directly in PHPUnit tests without relying on any external libraries, database calls. I need only function that can work in PHP environment itself to I can use them in PHPUnit tests without bootstrapping WordPress.

Also take into account that my PHPUnit environment uses mocks for the following WP functions:
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
- And config from `config-funcs.php` file.
- And  config from `config-classes.php` file.

I need list of functions that depend only on PHP and other WordPress functions that can be used as is in PHP and do not rely on any external libraries.


