# Runtime Documentation

## Scope

This document describes the WP-like runtime used by tests that will use code of this project to write it's unit tests. How this code (runtime) loads and what it contains.


## Entry Points

- Main API: `\Unitest_WP_Copy\Bootstrap::init()`
- Library entry script: `zero.php` (loads `wp-runtime/Bootstrap.php` and calls `Bootstrap::init()`)


## Runtime Layout

- `wp-runtime/Bootstrap.php`: loads copied code, stubs, and base globals/constants.
- `wp-runtime/boot-wp-constants.php`: base WP-like constants/environment values.
- `wp-runtime/boot-wp-globals.php`: WP PHP globals variables initialization.
- `wp-runtime/boot-wp-options.php`: option-like sources via `$GLOBALS['stub_wp_options']` and `$GLOBALS['stub_wp_site_options']`.
- `wp-runtime/init-parts/*`: extra init fragments required by some copied symbols.
- `wp-runtime/copy/functions/*`: parser-generated copied functions.
- `wp-runtime/copy/classes/*`: parser-generated copied classes.
- `wp-runtime/copy/mockable/*`: parser-generated functions with WP_Mock handler injection.
- `wp-runtime/custom-mocks/*`: manual runtime-adapted mocks.
- `wp-runtime/wp-line-extra/<wp-line>/*`: WP-line specific mocks, overlays, init-parts etc.
- `wp-runtime/wp-line-extra/<wp-line>/overlaps.php`: WP-line specific mocks that overlays copied symbols.
- `wp-runtime/copy/classes-statics/*`: parser-generated static-method compatibility functions.
- `wp-runtime/SYMBOLS-INFO.md`: index of available copied symbols.


## Bootstrap Effects

`Bootstrap::init()`:
- loads copied functions/classes and mock implementations;
- defines default WP-like constants when missing;
- initializes `$GLOBALS['stub_wp_options']`;
- initializes `$GLOBALS['stub_wp_site_options']` for multisite network-option values;
- sets `$_SERVER['HTTP_HOST']` from `$GLOBALS['stub_wp_options']->home`;
- initializes required WP-like globals used by copied code.

Runtime state is process-shared. Tests must restore changed globals/options in `setUp()`/`tearDown()`.

### `get_option()` and `get_site_option()` are runtime-adapted manual mocks.

INFO: Stored options (`$GLOBALS['stub_wp_options']`) take priority, so a defined mock cannot override or influence the current global stored values. 

`get_option()` uses this priority:

1. `pre_option_{$option}` and `pre_option` filters.
2. a value from `$GLOBALS['stub_wp_options']` and the `option_{$option}` filter.
3. a `WP_Mock` handler for an option missing from the store.
4. the `default_option_{$option}` filter OR `$default_value`.

`get_site_option()` uses same priority:

1. `pre_site_option_{$option}` and `pre_site_option` filters.
2. a value from `$GLOBALS['stub_wp_site_options']` and the `site_option_{$option}` filter.
3. a `WP_Mock` handler for an option missing from the store.
4. the `default_site_option_{$option}` filter OR `$default_value`.

For not `is_multisite()`, `get_site_option()` delegates to `get_option()`.


## Runtime Boundaries

This is not full WordPress:
- only selected symbols are included;
- DB/network/full bootstrap behavior is out of scope;
- some symbols require targeted stubs/mocks/adaptations.
