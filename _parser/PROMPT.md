GPT Prompt for uploaded file
============================

Collect the list of WP functions from the file I uploaded. I need only function that can work without WP environment (just php) - that I can use "as is" in phpunit. But Assume that 10up/wp_mock is used and it creates mocks for the following functions (so this functiona can be used in phpunit):
- add_action
- do_action
- add_filter
- apply_filters
- __
- _e
- _x
- _n
- esc_html__
- esc_html_e
- esc_html_x
- esc_attr__
- esc_attr_e
- esc_attr_x
- esc_html
- esc_attr
- esc_url
- esc_url_raw
- esc_js
- esc_textarea

The collected list of functions should not depends on any external lib or DB calls or other functions that uses DB calls because in PHP unit there is no DB and wordpress is not bootstraped. I need list of functions that depend only on PHP and other wordpress functons that can be used as is in PHP and do not rely on any external libraries.

If you dont know about some of functions and you need the code of the functions to answer correctly ask me to provide you with needed functions code.

Provede result in the followint format:
file1.php
- function1
- function2
file2.php
- function1
- function2
...


