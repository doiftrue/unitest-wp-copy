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
- And configs from `config-funcs.php`, `config-classes.php`, and `config-class-statics.php`.

I need a list of functions that depend only on PHP and other already available WordPress functions, and do not require external libraries.
This must include full transitive dependency validation (dependency of dependency, and so on), not only direct calls.

Important: if code uses options from `stub_wp_options.php`, then this function/method should be treated as usable in PHPUnit without bootstrapping WordPress, because these calls are stubbed via `$GLOBALS['stub_wp_options']`.

When updating parser configs (`config-funcs.php` / `config-classes.php` / `config-class-statics.php`):
- If a function/class is not suitable or not used in this project, comment it out.
- Do not delete such entries, so it remains visible that it exists in WordPress.


How Parser Works In This Project
================================

The parser is a whitelist-based copier of selected WordPress code, not a dependency analyzer.
So dependency-chain validation is a mandatory manual step before adding anything to config.

Core flow:
- Edit lists in `_parser/config-funcs.php`, `_parser/config-classes.php`, and (when needed) `_parser/config-class-statics.php`.
- Run `php _parser/run.php`.
- `run.php` creates `Updater` and passes:
  - destination folder: `copy/`
  - WP core source folder: `vendor/wordpress/wordpress`
  - function/class/static-method configs.

What `Updater` does:
- For each configured source file, reads original WP file.
- Extracts only selected top-level functions or one class using `Helpers::get_class_func_code_from_php_code()`.
- Rebuilds destination file content only after separator:
  - `// ------------------auto-generated---------------------`
- Wraps generated code with:
  - `if( ! function_exists( '...' ) ) : ... endif;`
  - `if( ! class_exists( '...' ) ) : ... endif;`
- Applies project-specific post-processing via `Extra_Replacer`:
  - replaces known `get_option()`/`get_site_option()` calls with `$GLOBALS['stub_wp_options']`;
  - applies static-method call replacement (`ClassName::method()` -> `ClassName__method()`) from `config-class-statics.php`.

Important constraints:
- Files in `copy/` are generated; avoid manual edits there unless adaptation is intentional.
- Parser only copies symbols listed in config files.
- If a configured function is missing in source file, parser throws an exception.


Mandatory Dependency-Chain Rule (Functions, Classes, Static Methods)
====================================================================

Before adding any function/class/static-method to parser configs, validate the full transitive dependency chain.

Hard rule:
- A function/class/static-method is allowed only if every dependency in the chain is:
  - already available in this project (`copy/`, `copy/mocks/`, `copy/init-parts/`, `src/*`), or
  - added in the same change and passes the same dependency-chain rule recursively.
- If dependency A requires dependency B, B must be checked with the same strict criteria, recursively until chain end.
- If at least one dependency in the chain is incompatible with this project ideology (DB-bound runtime, full WP bootstrap, network I/O, unsupported filesystem/runtime coupling, etc.), then the top-level function/class/static-method is not allowed.
- Never add a function/class/static-method with unresolved dependency "for later fix". In this project, unresolved dependency means symbol is not suitable now.
- When a function/class/static-method is rejected, keep it commented in config and include a short reason mentioning the blocking chain segment.


Step-By-Step: Add More WP Core Functions
========================================

1) Select candidate functions
- Start from a specific WP core file. Find it in `vendor/wordpress/wordpress`.
- Choose functions that are pure PHP or depend only on already available copied functions/classes/mocks/init.
- If function needs DB/filesystem/network/full runtime, usually skip it.
- Build dependency chain for each candidate (direct + transitive calls).
- Candidate is valid only if whole chain can be satisfied by existing project symbols or by symbols that are also valid to add now.

2) Check compatibility with current test environment
- Available stubs/constants/init are loaded by `zero.php`, `src/constants.php`, `src/stub_wp_options.php`, and `copy/init-parts/*`.
- Mocked compatibility functions are in `copy/mocks/wp-includes/*`.
- If option access is covered by `$GLOBALS['stub_wp_options']`, function is acceptable.
- Reject candidate if any dependency in its chain remains unresolved or requires unsupported runtime behavior.

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

Use the same flow as "Step-By-Step: Add More WP Core Functions":
- select candidate;
- validate compatibility and full transitive dependency chain (same strict rule from section above);
- update parser config;
- regenerate;
- add tests;
- run suite;
- final review.

Class-specific differences:
- Candidate filter: prefer pure PHP/in-memory classes; skip classes requiring full WP runtime.
- Config target: use `_parser/config-classes.php`.
- Dependency graph: include full minimal class/function chain needed by the class.
- Tests: one class per file in `tests/classes/...` with `__Test.php`; methods use `test__*` without class-name duplication.
- If class is not independent in current env, add explicit `test__not_independent_*` with `expectException( Error::class )` (see `tests/INSTRUCTIONS.md`).


Step-By-Step: Copy Static Class Methods As Functions (Experimental)
===================================================================

This is an in-progress compatibility mechanism used in `copy/classes-statics/`.
Use it only when whole class copy is not suitable, but one utility-like static method is needed by another copied symbol.

When to use:
- Source class is not suitable for this project as a whole.
- Needed static method is isolated and behaves like pure utility function.
- Full dependency chain of that method is valid for this project (same strict rule as above).

How it works:
- Source: `ClassName::methodName()`.
- Copied symbol: plain function `ClassName__methodName()`.
- Parser stores such functions in `copy/classes-statics/ClassName.php`.
- During parser replace phase, calls are rewritten:
  - `ClassName::methodName(...)` -> `ClassName__methodName(...)`.

Workflow:
1) Add source class + methods in `_parser/config-class-statics.php` using explicit format:
   - `'path/to/class-file.php' => [ 'class' => 'ClassName', 'methods' => [ 'methodName' => '' ] ]`
2) Run `php _parser/run.php` (or `make run.parser`).
3) Verify generated function in `copy/classes-statics/ClassName.php`.
4) Verify call replacement happened in copied code.
5) Add/update tests for the behavior that depends on this method.

Important:
- Keep this mechanism minimal and explicit. Do not move arbitrary class methods here.
- This is not a full class emulation; only selected utility-like static methods are allowed.
