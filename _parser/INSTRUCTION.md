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


How Parser Works In This Project
================================

The parser is a whitelist-based copier of selected WordPress code, not a dependency analyzer.

Core flow:
- Edit lists in `_parser/config-funcs.php` and `_parser/config-classes.php`.
- Run `php _parser/run.php`.
- `run.php` creates `Updater` and passes:
  - destination folder: `copy/`
  - WP core source folder: `vendor/wordpress/wordpress`
  - function/class configs.

What `Updater` does:
- For each configured source file, reads original WP file.
- Extracts only selected top-level functions or one class using `Parser_Helpers::get_class_func_code_from_php_code()`.
- Rebuilds destination file content only after separator:
  - `// ------------------auto-generated---------------------`
- Wraps generated code with:
  - `if( ! function_exists( '...' ) ) : ... endif;`
  - `if( ! class_exists( '...' ) ) : ... endif;`
- Applies project-specific post-processing via `Extra_Replacer`:
  - replaces known `get_option()`/`get_site_option()` calls with `$GLOBALS['stub_wp_options']`;
  - replaces `get_bloginfo( 'version' )` with fixed WP version string;
  - applies static-method compatibility replacement (for `WP_Http::make_absolute_url`).

Important constraints:
- Files in `copy/` are generated; avoid manual edits there unless adaptation is intentional.
- Parser only copies symbols listed in config files.
- If a configured function is missing in source file, parser throws an exception.


Step-By-Step: Add More WP Core Functions
========================================

1) Select candidate functions
- Start from a specific WP core file. Find it in `vendor/wordpress/wordpress`.
- Choose functions that are pure PHP or depend only on already available copied functions/classes/mocks/init.
- If function needs DB/filesystem/network/full runtime, usually skip it.

2) Check compatibility with current test environment
- Available stubs/constants/init are loaded by `zero.php`, `src/constants.php`, `src/stub_wp_options.php`, and `copy/init-parts/*`.
- Mocked compatibility functions are in `copy/mocks/wp-includes/*`.
- If option access is covered by `$GLOBALS['stub_wp_options']`, function is acceptable.

3) Update parser config
- Add function name into `_parser/config-funcs.php` under the correct WP source file key.
- If function exists but is not suitable for this project, keep it commented (do not delete).

4) Add/adjust compatibility only when needed
- If new function requires small safe adaptation, add it via:
  - `Extra_Replacer` rule, or
  - dedicated helper/adapted copy file (existing project pattern).
- Keep adaptations minimal and explicit.

5) Regenerate copied code
- Run:
  - `php _parser/run.php`
- Confirm target file in `copy/functions/...` was updated and no parser warnings/errors occurred.

6) Add tests
- Add/update PHPUnit tests in `tests/functions/...`.
- Follow `tests/INSTRUCTIONS.md`:
  - one function per test method;
  - `test__function_name` naming convention;
  - cover basic logic branches enough to ensure function works without full WP runtime.

7) Run test suite
- Run:
  - `make phpunit`
- If failures are due to missing runtime pieces, either:
  - add minimal mock/stub/init support, or
  - revert function from active config (leave commented note).

8) Final review before commit
- Ensure generated `copy/` changes correspond only to intended functions.
- Ensure comments in config explain why entries are commented out (if any).


Step-By-Step: Add More WP Core Classes
======================================

1) Select candidate classes
- Prefer classes with pure PHP logic and in-memory behavior.
- Avoid classes requiring full WP runtime (DB queries, post/taxonomy loaders, block registries that depend on extra runtime, etc.).

2) Check class dependency chain
- Before adding a class, inspect all classes/functions it directly needs.
- If class needs helper classes from WP core, add the full minimal chain to `_parser/config-classes.php`.
- Keep chain comments in config so it is clear why related classes are grouped.

3) Update parser config
- Add class mapping into `_parser/config-classes.php`.
- If class is known but not suitable, keep it as commented note (do not delete awareness comments).

4) Regenerate copied code
- Run `make run.parser` (or `php _parser/run.php`).
- Verify new/updated files in `copy/classes/`.

5) Add class tests
- Add one test file per class in `tests/classes/...` with `__Test.php` suffix.
- Use `test__*` method names without class name duplication in method name.
- Validate runtime independence:
  - positive smoke test for independent behavior;
  - explicit `test__not_independent_*` with expected `Error` for known unresolved runtime dependencies.

6) Run suite and confirm behavior
- Run `make phpunit`.
- If test shows unresolved dependency:
  - either add minimal missing dependency chain/stub (if still within project ideology), or
  - keep class marked/covered as not independent in tests and config comments.
