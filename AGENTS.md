# AGENTS.md

## What this project is

`unitest-wp-copy` is a PHP library for unit testing WordPress-related code without loading the full WordPress runtime.

The project includes:
- copies of selected WordPress Core functions and classes that run on plain PHP only (no DB or external services);
- minimal stubs/environment initialization so these functions can be safely executed in PHPUnit;
- tests that verify basic behavior of included functions.

Main bootstrap API: `\Unitest_WP_Copy\Bootstrap::init()`.
Entry point script: `zero.php` (includes `wp-runtime/Bootstrap.php` and calls `\Unitest_WP_Copy\Bootstrap::init()`).

## Project structure

- `zero.php`  
  Library entry point. Includes `wp-runtime/Bootstrap.php` and calls `\Unitest_WP_Copy\Bootstrap::init()`.

- `wp-runtime/copy/`  
  Copied WordPress functions/classes.

  - `wp-runtime/SYMBOLS-INFO.md`  
    Reference list of symbols included in `wp-runtime/copy/` (functions/classes/statics/mocks) for quick lookup.

  - `wp-runtime/copy/classes-statics/`  
    Experimental compatibility layer for selected static class methods copied as plain functions.
    Naming rule: `ClassName::methodName()` -> `ClassName__methodName()`.
    Used when whole class is not suitable for this project, but utility-like static method is needed as dependency.

  - Mock-friendly function layer.
    - `wp-runtime/copy/mockable/` — parser-generated copies of original WP functions with injected `WP_Mock` handler check at function start.
    - `wp-runtime/copy/mocks/` — manually maintained mocks where logic is intentionally changed for this project runtime.

- `wp-runtime/init-parts/`
  Minimal WP initialization fragments required to run some functions.

- Environment initialization for copied functions/classes.

  - `wp-runtime/Bootstrap.php`  
    Main bootstrap implementation. Loads copies from `wp-runtime/copy/`, stubs from `wp-runtime/src/`, and initializes base WP-like globals/constants.

  - `wp-runtime/stub-wp-options.php`  
    Stub for `get_option()`-like calls via `$GLOBALS['stub_wp_options']`. Some kinds mock of DB-stored options, some just return hardcoded values.

  - `wp-runtime/base-wp-constants.php`  
    Base WP constants and environment values for test execution.
  
- `wp-core/`  
  WordPress core code, used as source for copied functions/classes. Not loaded in runtime, only for reference and copy.


### `_parser/`
  Separate part of project that creates `wp-runtime/copy/` code copiing it from WP core code.
  Internal generator/updater for copied functions, mockable functions, classes, and selected static class methods.
  - `config/functions/` — function lists split by WP source file path;
    - use value `'mockable'` for symbols that should be generated into `wp-runtime/copy/mockable/*` with injected WP_Mock handler.
  - `config/classes.php` — class list;
  - `config/static-methods.php` — selected static class methods copied as plain functions;
  - `_parser/INSTRUCTION.md` — instructions for updating copied code.
  - `_parser/run.php` — update runner.
    - Should be run with `make run.parser` command.

- `tests/`  
  PHPUnit tests for whole project.
  - `tests/functions/` — function tests.
  - `tests/classes-statics/` — tests for static class methods copied as plain functions (`wp-runtime/copy/classes-statics/`).
  - `tests/classes/` — class tests.
  - `tests/mocks/` — tests for mockable/manual mock implementations from `wp-runtime/copy/mockable/` and `wp-runtime/copy/mocks/` (including WP_Mock-handler behavior).
  It tests how the copied functions/classes work in the provided environment. The current WP-like test env loaded as if it used on another project as phpunit test WP environment, and all WP functions/classes are tested if they work correctly without real WP environment (without DB, external services, etc).
  
- `worktrees`
  - Artifact directory for release branches (`wp-6.8`, `wp-6.9`, etc). Not loaded in runtime, only for storing release.


## Quick workflow

1. Install dependencies:
   `make composer.install`
2. Run tests:
   `make phpunit`
3. If you need to refresh copied functions/classes:
   `make run.parser`
4. Always rerun tests after refreshing copies.


## Important notes for agents

- Do not manually remove or alter logic in `wp-runtime/copy/` without understanding the impact: these files are synchronized via `_parser`.
- Never works with `worktrees` treat it as an artifact directory for releases only.
- When adding a new function/class/static-method shim, update the relevant `config/*.php` first, then run `_parser/run.php`.
- Put value `'mockable'` in `config/functions/*` when you need original WP logic with injected WP_Mock handler.
- Keep `wp-runtime/copy/mocks/wp-includes/*` only for manual mocks with intentionally changed behavior.
- Detailed parser workflow and class/function inclusion rules: see `_parser/INSTRUCTION.md`.
- Detailed testing conventions (naming, file layout, class-vs-function test rules): see `tests/INSTRUCTIONS.md`.
- Project code and code comments must be written in English.
