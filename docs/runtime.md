# Runtime Documentation

## Scope

This document describes the WP-like runtime used by tests that will use code of this project to write it's unit tests. How this code (runtime) loads and what it contains.


## Entry Points

- Main API: `\Unitest_WP_Copy\Bootstrap::init()`
- Library entry script: `zero.php` (loads `wp-runtime/Bootstrap.php` and calls `Bootstrap::init()`)


## Runtime Layout

- `wp-runtime/Bootstrap.php`: loads copied code, stubs, and base globals/constants.
- `wp-runtime/base-wp-constants.php`: base WP-like constants/environment values.
- `wp-runtime/stub-wp-options.php`: option-like source via `$GLOBALS['stub_wp_options']`.
- `wp-runtime/init-parts/*`: extra init fragments required by some copied symbols.
- `wp-runtime/copy/functions/*`: parser-generated copied functions.
- `wp-runtime/copy/classes/*`: parser-generated copied classes.
- `wp-runtime/copy/mockable/*`: parser-generated functions with WP_Mock handler injection.
- `wp-runtime/mocks/*`: manual runtime-adapted mocks.
- `wp-runtime/wp-line-extra/<wp-line>/*`: WP-line specific mocks, overlays, init-parts etc.
- `wp-runtime/wp-line-extra/<wp-line>/overlaps.php`: WP-line specific mocks that overlays copied symbols.
- `wp-runtime/copy/classes-statics/*`: parser-generated static-method compatibility functions.
- `wp-runtime/SYMBOLS-INFO.md`: index of available copied symbols.


## Bootstrap Effects

`Bootstrap::init()`:
- loads copied functions/classes and mock implementations;
- defines default WP-like constants when missing;
- initializes `$GLOBALS['stub_wp_options']`;
- sets `$_SERVER['HTTP_HOST']` from `$GLOBALS['stub_wp_options']->home`;
- initializes required WP-like globals used by copied code.

Runtime state is process-shared. Tests must restore changed globals/options in `setUp()`/`tearDown()`.


## Runtime Boundaries

This is not full WordPress:
- only selected symbols are included;
- DB/network/full bootstrap behavior is out of scope;
- some symbols require targeted stubs/mocks/adaptations.

