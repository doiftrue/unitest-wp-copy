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
- And effective parser configs merged from:
  - base config files in `config/*`;
  - optional WP-line override files in `config/<wp-line>/*`.

I need a list of functions that depend only on PHP and other already available WordPress functions, and do not require external libraries.
This must include full transitive dependency validation (dependency of dependency, and so on), not only direct calls.

Important: if code uses options from `stub-wp-options.php`, then this function/method should be treated as usable in PHPUnit without bootstrapping WordPress, because these calls are stubbed via `$GLOBALS['stub_wp_options']`.

When updating parser configs:
- Base latest-line config files are:
  - `config/WP-VERSION-LINE` (base config metadata, target WP minor line `major.minor`, e.g. `6.8`)
  - `config/functions/<wp-source-file>.php`
  - `config/classes.php`
  - `config/static-methods.php`
- Line overrides for older WP lines are:
  - `config/<wp-line>/functions/<wp-source-file>.php`
  - `config/<wp-line>/classes.php`
  - `config/<wp-line>/static-methods.php`
- If a function/class is not suitable or not used in this project, comment it out.
- Do not delete such entries, so it remains visible that it exists in WordPress.
- In config comments, always list exact symbol names (e.g. `wp_get_theme`, `wp_get_themes`), never masks/wildcards like `wp_get_theme*`.


Versioned Config Merge Model (Base + Overrides)
===============================================

The project keeps one canonical config for the newest supported WP line in top-level `config/*`.
The target WP minor line for this base config is stored in `config/WP-VERSION-LINE`.
WP line (`major.minor`) is always derived dynamically from parser runtime WP version.

For older lines, use small override configs in `config/<wp-line>/*` with the same file structure.
Example: `config/6.7/functions/wp-includes/formatting.php`.

Parser merge behavior:
- Parser reads current WP version from `wordpress/wp-includes/version.php`.
- Parser derives WP line as `major.minor` (for example `6.8`).
- Parser loads base config from `config/*`.
- If folder `config/<major.minor>/` exists, parser merges it into base config.

Merge rules:
- scalar override value: add/replace value in merged config;
- array override value: merge recursively;
- `false` override value: remove this key from merged config.

Function move rule (between WP lines):
- remove symbol from file where it exists in base config via `false`;
- add symbol into the target file in the same override line config.

This will reflect changes for old WP-line config without copying all config.

Lifecycle rule:
- Base config always tracks newest supported WP line.
- When new WP line is adopted:
  - update `config/WP-VERSION-LINE` with new WP minor line (`major.minor`);
  - update top-level `config/*` for new line;
  - create `config/<previous-line>/` with only rollback differences.


How Parser Works In This Project
================================

The parser is a whitelist-based copier of selected WordPress code, not a dependency analyzer.
So dependency-chain validation is a mandatory manual step before adding anything to config.

Core flow:
- Edit parser config for target line:
  - newest line: top-level `config/*`;
  - older line: `config/<wp-line>/*` override files (only changed keys).
- Run `php _parser/run.php`.
- `run.php` creates `Updater` and passes:
  - destination folder: `copy/`
  - WP core source folder: `wordpress`
  - merged function/class/static-method configs.

What `Updater` does:
- For each configured source file, reads original WP file.
- Extracts only selected top-level functions or one class using `Helpers::get_class_func_code_from_php_code()`.
- Rebuilds destination file content only after separator:
  - `// ------------------auto-generated---------------------`
- Wraps generated code with:
  - `if( ! function_exists( '...' ) ) : ... endif;`
  - `if( ! class_exists( '...' ) ) : ... endif;`
- Function config value format:
  - `'function_name' => '<since-version>'`
  - `'function_name' => '<since-version> mockable'`
- In WP-line override files, you can remove an inherited function with:
  - `'function_name' => false`
- If configured `<since-version>` is higher than current `wp_version`, parser skips that function.
- For functions marked as `mockable`:
  - copies original WP function body as-is;
  - injects WP_Mock handler check at function start:
    - `\Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ )`
    - `\Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() )`.
- Applies project-specific post-processing via `Extra_Replacer`:
  - replaces known `get_option()`/`get_site_option()` calls with `$GLOBALS['stub_wp_options']`;
  - applies static-method call replacement (`ClassName::method()` -> `ClassName__method()`) from merged static-method config.

Important constraints:
- Files in `copy/` are generated; avoid manual edits there unless adaptation is intentional.
- Parser only copies symbols listed in config files.
- If a configured function is missing in source file, parser throws an exception.


Parser Code Writing Style
=========================

When changing parser code (`_parser/src/*`), prefer strict and simple implementation.

Rules:
- Keep logic direct and readable; avoid defensive programming by default.
- Do not add extra guard checks for states that should not happen in normal flow.
- Do not add fallback branches just to avoid fatals/warnings in logically broken states.
- If assumptions are wrong, let the code fail fast (or produce incorrect output), then fix it via tests.
- Main goal: minimal branching and no unnecessary checks, so parser code is easier to read and maintain manually.

Example of what to avoid unless truly required by design:

```php
private function build_functions_config( string $base_dir ): array {
	if( ! is_dir( $base_dir ) ){
		return [];
	}
}
```


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
- Start from a specific WP core file. Find it in `wordpress`.
- Choose functions that are pure PHP or depend only on already available copied functions/classes/mocks/init.
- If function needs DB/filesystem/network/full runtime, usually skip it.
- Build dependency chain for each candidate (direct + transitive calls).
- Candidate is valid only if whole chain can be satisfied by existing project symbols or by symbols that are also valid to add now.

2) Check compatibility with current test environment
- Available stubs/constants/init are loaded by `zero.php`, `src/base-wp-constants.php`, `src/stub-wp-options.php`, and `copy/init-parts/*`.
- Mocked compatibility functions are in `copy/mocks/auto/*` (parser-generated) and `copy/mocks/wp-includes/*` (manual).
- If option access is covered by `$GLOBALS['stub_wp_options']`, function is acceptable.
- Reject candidate if any dependency in its chain remains unresolved or requires unsupported runtime behavior.

3) Update parser config
- Add function into config file for target line:
  - newest line: `config/functions/<wp-source-file>.php` (for example `config/functions/wp-includes/formatting.php`);
  - older line: `config/<wp-line>/functions/<wp-source-file>.php`.
- If function exists but is not suitable for this project, keep it commented (do not delete).
- Set config value with function "since" version:
  - regular function: `'function_name' => '<since-version>'`
  - mockable function: `'function_name' => '<since-version> mockable'`
- To remove inherited function for one older line, set:
  - `'function_name' => false` in `config/<wp-line>/functions/<wp-source-file>.php`.

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
- Config target:
  - newest line: `config/classes.php`;
  - older lines: `config/<wp-line>/classes.php`.
- Dependency graph: include full minimal class/function chain needed by the class.
- Tests: one class per file in `tests/classes/...` with `__Test.php`; methods use `test__*` without class-name duplication.
- If class is not independent in current env, add explicit `test__not_independent_*` with `expectException( Error::class )` (see `tests/INSTRUCTIONS.md`).


Step-By-Step: Copy Auto-Mock Functions (Original WP Logic + Handler)
====================================================================

Use this when function logic should stay identical to WP core, but direct WP_Mock handler override is needed in tests.

How it works:
- Config source:
  - newest line: `config/functions/<wp-source-file>.php`;
  - older lines: `config/<wp-line>/functions/<wp-source-file>.php`;
  with value `'<since-version> mockable'`.
- Destination: `copy/mocks/auto/<wp-source-file>.php`.
- Parser copies original function code and injects handler check at function start.
- Generated function is wrapped with `if ( ! function_exists( ... ) )`.

Rules:
- Auto-mock is for "same WP logic + handler injection only".
- If function needs behavior changes for this project runtime, keep/manual-implement it in `copy/mocks/wp-includes/*`.

Workflow:
1) Add function to target-line config with value `'<since-version> mockable'`:
   - `config/functions/<wp-source-file>.php` (newest line), or
   - `config/<wp-line>/functions/<wp-source-file>.php` (older line override).
2) `make run.parser`.
3) Verify generated code in `copy/mocks/auto/...`.
4) Add/update tests in `tests/mocks/...`:
   - one test for fallback/original behavior;
   - one test for `WP_Mock::userFunction(...)` override behavior.


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
1) Add source class + methods in target-line static-method config:
   - newest line: `config/static-methods.php`;
   - older lines: `config/<wp-line>/static-methods.php`;
   using explicit format:
   - `'path/to/class-file.php' => [ 'class' => 'ClassName', 'methods' => [ 'methodName' => '' ] ]`
2) Run `php _parser/run.php` (or `make run.parser`).
3) Verify generated function in `copy/classes-statics/ClassName.php`.
4) Verify call replacement happened in copied code.
5) Add/update tests for the behavior that depends on this method.

Important:
- Keep this mechanism minimal and explicit. Do not move arbitrary class methods here.
- This is not a full class emulation; only selected utility-like static methods are allowed.
