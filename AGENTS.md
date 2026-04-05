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
  Library entry point. Loads files from `copy/`, stubs from `src/`, and init parts from `copy/init-parts/`.

- `copy/functions/` и `copy/functions/wp-includes/`  
  Copied WordPress functions.

- `copy/classes/`  
  Copied WordPress classes.

- `copy/init-parts/wp-includes/`  
  Minimal WP initialization fragments required to run some functions.

- `copy/mocks.php`  
  Helper mocks/stubs for compatibility.

- `src/stub_wp_options.php`  
  Stub for `get_option()`-like calls via `$GLOBALS['stub_wp_options']`.

- `src/constants.php`  
  Base constants and environment values for test execution.

- `_parser/`  
  Internal generator/updater for copied functions and classes.
  - `_parser/config-funcs.php` — function list;
  - `_parser/config-classes.php` — class list;
  - `_parser/run.php` — update runner.

- `tests/`  
  PHPUnit tests for included functions.

- `phpunit.xml`  
  Test run configuration.

- `composer.json`  
  Package metadata and dev dependencies (`phpunit`, `wordpress/wordpress`).

## Quick workflow

1. Install dependencies:
   `make composer.install`
2. Run tests:
   `make phpunit`
3. If you need to refresh copied functions/classes:
   `php _parser/run.php`
4. Always rerun tests after refreshing copies.

## Important notes for agents

- Do not manually remove or alter logic in `copy/` without understanding the impact: these files are synchronized via `_parser`.
- When adding a new function/class, update the relevant `_parser/config-*.php` first, then run `_parser/run.php`.
- When adding tests, keep them isolated from full WordPress runtime and cover basic logic branches without extra dependencies.
- See `tests/INSTRUCTIONS.md` when writing tests.
