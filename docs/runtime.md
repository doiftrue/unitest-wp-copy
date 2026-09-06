# Runtime Documentation

## Scope

This document describes the WP-like runtime that this project provides to tests: how it loads and what it contains.


## Entry Points

- Main API: `\Unitest_WP_Copy\Bootstrap::init()`
- Library entry script: `zero.php` (loads `wp-runtime/Bootstrap.php` and calls `Bootstrap::init()`)


## Runtime Layout

- `wp-runtime/Bootstrap.php`: loads copied code, stubs, and base globals/constants.
- `wp-runtime/boot-wp-constants.php`: base WP-like constants/environment values.
- `wp-runtime/boot-wp-globals.php`: WP PHP globals variables initialization.
- `wp-runtime/boot-wp-hooks.php`: runtime-specific subset of WordPress default hook registration.
- `wp-runtime/boot-wp-options.php`: option-like sources via `$GLOBALS['stub_wp_options']` and `$GLOBALS['stub_wp_site_options']`.
- `wp-runtime/init-parts/*`: extra init fragments required by some copied symbols.
- `wp-runtime/copy/functions/*`: parser-generated copied functions.
- `wp-runtime/copy/classes/*`: parser-generated copied classes.
- `wp-runtime/copy/mockable/*`: parser-generated functions with WP_Mock handler injection.
- `wp-runtime/copy/classes-statics/*`: parser-generated static-method compatibility functions.
- `wp-runtime/copy/traits/*`: parser-generated traits containing selected original instance methods for runtime-adapted classes.
- `wp-runtime/custom-mocks/*`: manual runtime-adapted mocks.
- `wp-runtime/wp-line-extra/<wp-line>/*`: WP-line specific mocks, overlays, init-parts etc. (override mechanism: [parser.md](parser.md)).
- `wp-runtime/wp-line-extra/<wp-line>/overlaps.php`: WP-line specific mocks that overlay copied symbols.
- `SYMBOLS-INFO.md` (repo root): index of all available symbols (functions, classes).


## Bootstrap Effects

`Bootstrap::init()`:
- loads copied functions/classes and mock implementations;
- defines default WP-like constants when missing;
- initializes `$GLOBALS['stub_wp_options']`;
- initializes `$GLOBALS['stub_wp_site_options']` for multisite network-option values;
- initializes the in-memory `$wp_registered_sidebars` registry;
- defines block-template-part area constants used by copied template helpers;
- sets `$_SERVER['HTTP_HOST']` from `$GLOBALS['stub_wp_options']->home`;
- initializes required WP-like globals used by copied code.

Runtime state is process-shared. Tests must restore changed globals/options in `setUp()`/`tearDown()`.

The default option stores include deterministic values required by copied
multisite upload/registration policy functions and HTTPS migration predicates.

### Runtime-adapted classes with copied methods

When a class cannot be copied as a whole, a custom runtime adapter may use a trait generated from selected original WordPress instance methods. The trait contains unchanged method implementations; the custom class supplies the reduced runtime state and any intentionally adapted dependencies.

Generated traits are available under the `Unitest_WP_Copy` namespace in `wp-runtime/copy/traits/` and may be used by manual mocks that need the configured original WordPress methods. The consuming mock must provide every property and excluded method referenced through `$this`.

The public surface of every runtime adapter (methods, properties, `[wp]`/`[adapted]` origin marks) is auto-generated into the first section of `SYMBOLS-INFO.md`; see [parser.md](parser.md#symbol-list-generation).

Current consumers:

Runtime-adapted classes use the exact original WordPress class name, including
its letter case, followed by the `__Runtime` suffix. Their source files retain
the filenames of the corresponding WordPress implementations.

| Runtime adapter | Generated trait | Manual adaptation |
| --- | --- | --- |
| `WP_REST_Server__Runtime` | `WP_REST_Server__Copied_Methods` | Live HTTP serving is disabled; status and headers are recorded in memory; the root index omits theme/media/post enrichment. A guarded alias exposes it as global `WP_REST_Server`. |
| `wpdb__Runtime` | `wpdb__Copied_Methods` | `_real_escape()` uses `addslashes()` because the runtime has no database connection; standard table-name properties support SQL builders. |

### REST runtime

The runtime installs the safe `rest_handle_options_request` subset of
`rest_api_default_filters()` on `rest_pre_dispatch`, so OPTIONS participates in
direct in-memory dispatch. Core's
`rest_send_allow_header` and `rest_filter_response_fields` post-dispatch filters
are not installed automatically because WordPress applies them from
`serve_request()`, which is outside this runtime's scope. The functions remain
available for explicit testing.

`rest_get_server()` stores its instance in `$GLOBALS['wp_rest_server']`.
Tests that register routes must reset that global in `tearDown()`:

```php
unset( $GLOBALS['wp_rest_server'] );
```

The default capability/authentication adapters return `false` from
`current_user_can()` and `is_user_logged_in()`. Both can be overridden with
`WP_Mock::userFunction()`.

Resetting `$GLOBALS['wp_filter']` also removes the OPTIONS integration and any
other runtime-installed hooks. Tests that clear the hook registry must restore
the filters they rely on.

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
