# AGENTS.md

## What this project is

`unitest-wp-copy` is a PHP library for unit testing WordPress-related code without loading the full WordPress runtime.

The project includes:
- copies of selected WordPress Core functions and classes that run on plain PHP only (no DB or external services);
- minimal stubs/environment initialization so these functions can be safely executed in PHPUnit;
- tests that verify basic behavior of included functions.

Main entry point: `zero.php` (loads copied functions/classes and initializes base WP constants/globals).

## Project structure

- `zero.php`  
  Library entry point. Loads files from `copy/`, stubs from `src/`.

- `copy/`  
  Copied WordPress functions/classes.

  - `copy/classes-statics/`  
    Experimental compatibility layer for selected static class methods copied as plain functions.
    Naming rule: `ClassName::methodName()` -> `ClassName__methodName()`.
    Used when whole class is not suitable for this project, but utility-like static method is needed as dependency.

  - `copy/init-parts/`  
    Minimal WP initialization fragments required to run some functions.

  - `copy/mocks/`  
    WP functions/methods which code was changed (mocked) to work like in WP environment, but without loading WP. For example, `switch_to_blog()` is mocked to change the value of `$GLOBALS['current_blog_id']` instead of loading the new blog.

- `src/`  
  Stubs and environment initialization for copied functions/classes.

  - `src/stub_wp_options.php`  
    Stub for `get_option()`-like calls via `$GLOBALS['stub_wp_options']`. Some kinds mock of DB-stored options, some just return hardcoded values.

  - `src/constants.php`  
    Base WP constants and environment values for test execution.
  
- `wordpress/`  
  WordPress core code, used as source for copied functions/classes. Not loaded in runtime, only for reference and copy.


### `_parser/`
  Separate part of project that creates `copy/` code copiing it from WP core code.
  Internal generator/updater for copied functions, classes, and selected static class methods.
  - `_parser/config/functions/` — function lists split by WP source file path;
  - `_parser/config/classes.php` — class list;
  - `_parser/config/static-methods.php` — selected static class methods copied as plain functions;
  - `_parser/INSTRUCTION.md` — instructions for updating copied code.
  - `_parser/run.php` — update runner.
    - Should be run with `make run.parser` command.

- `tests/`  
  PHPUnit tests for whole project.
  - `tests/functions/` — function tests.
  - `tests/classes-statics/` — tests for static class methods copied as plain functions (`copy/classes-statics/`).
  - `tests/classes/` — class tests.
  - `tests/mocks/` — tests for mock implementations from `copy/mocks/` (including WP_Mock-handler behavior).
  It tests how the copied functions/classes work in the provided environment. The current WP-like test env loaded as if it used on another project as phpunit test WP environment, and all WP functions/classes are tested if they work correctly without real WP environment (without DB, external services, etc).


## Quick workflow

1. Install dependencies:
   `make composer.install`
2. Run tests:
   `make phpunit`
3. If you need to refresh copied functions/classes:
   `make run.parser`
4. Always rerun tests after refreshing copies.


## Important notes for agents

- Do not manually remove or alter logic in `copy/` without understanding the impact: these files are synchronized via `_parser`.
- When adding a new function/class/static-method shim, update the relevant `_parser/config-*.php` first, then run `_parser/run.php`.
- Detailed parser workflow and class/function inclusion rules: see `_parser/INSTRUCTION.md`.
- Detailed testing conventions (naming, file layout, class-vs-function test rules): see `tests/INSTRUCTIONS.md`.
- Project code and code comments must be written in English.
