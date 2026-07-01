# Symbol Eligibility Rules

## Scope

This document defines how to decide whether a function/class/static-method symbol is suitable for this project before adding it to parser config.
It is a pre-parser analysis policy.

Dependency documents:
- Runtime constraints and available environment: [runtime.md](runtime.md)
- Test conventions: [tests.md](tests.md)
- Config model and value formats: [config.md](config.md)


## Rules for Symbol Eligibility

- Validate the full transitive dependency chain before adding any symbol.
- A symbol is eligible only if every dependency in its chain is:
  - already available in current runtime (`wp-runtime/SYMBOLS-INFO.md`), or
  - added in the same change and validated by these same rules recursively, or
- Reject a symbol if any dependency requires unsupported runtime behavior (DB, full WordPress bootstrap, network I/O, admin/request lifecycle, or similarly heavy runtime coupling).
- Do not add unresolved dependencies "for later".
- If option access is covered by `$GLOBALS['stub_wp_options']`, treat it as compatible with this runtime.
- The symbol should solve an in-memory / pure-PHP task that is useful for unit tests.
- Symbol behavior should be predictable and not tightly coupled to a "live" runtime (DB, HTTP, admin/request lifecycle).
- If only a small part works, but key public behavior is not viable in this isolated runtime, the symbol is not suitable and should stay disabled in config.
- If whole chain depends on one root dependency that logically should be mocked in tests (e.g. WP_REST_Server), then suggest to include symbols but only after discussion.


## Workflow: Add Symbols

1. Select candidate symbols from a concrete `wp-core` file.
2. Validate symbol eligibility using rules in this document.
3. For every eligible function, run the Auto-Mockable Review below.
4. Update parser config for target WP line using rules from [config.md](config.md).
5. Regenerate copies: `make parser.run`.
6. Add/update tests following [tests.md](tests.md).
7. Run full test suite: `make phpunit`.
8. If symbol remains incompatible, keep it disabled/commented in config with reason.


## Auto-Mockable Review

Review every eligible function for `mockable`; do not assume a regular copy by default.

Mark a function `mockable` when all of the following are true:
- its original WP implementation is dependency-safe and useful as the default fallback;
- it is a boundary that code under test may reasonably need to override, such as an environment, runtime-state, configuration, clock, locale, URL, registry, or feature-state provider;
- callers should be able to control its result without constructing or mutating the complete WP runtime state;
- overriding it does not require behavior different from its WP contract.

Prefer the lowest shared boundary in a delegation chain. For example, make
`get_admin_url()` mockable while keeping `admin_url()` regular: the wrapper retains
the original WP delegation and receives the configured `get_admin_url()` result.
Do not automatically mark every wrapper and caller in the chain as mockable.

Keep a function regular when its result is determined entirely by its arguments and
available deterministic helpers, or when normal filters/actions already provide the
intended test seam without requiring unavailable runtime state.

`mockable` is not a compatibility workaround:
- if the original logic requires unsupported runtime behavior, reject the symbol;
- if the runtime implementation must intentionally differ from WP core, use a manual
  mock in `wp-runtime/mocks/*`;
- do not use `mockable` only to hide missing transitive dependencies or fatal default
  behavior.

For each function selected as `mockable`, tests must prove both paths:
- original WP fallback behavior when no handler is registered;
- `WP_Mock::userFunction(...)` override behavior.


## Workflow: Auto-Mockable Functions

Use Auto-Mockable only for "original WP logic + handler injection":
- mark function config value as `'<since-version> mockable'` (see [config.md](config.md));
- parser generates into `wp-runtime/copy/mockable/...`;
- function start includes `WP_Mock_Utils` handler check/call;
- add tests for:
  - default fallback behavior;
  - `WP_Mock::userFunction(...)` override behavior.

If runtime behavior must diverge from WP core, a manual mock in `wp-runtime/mocks/*` should be implemented.


## Workflow: Static Class Methods Compatibility

Use this only when:
- full class copy is not suitable;
- one static method is utility-like and dependency-safe.
- this utility-like method is used by another symbol that is eligible for copying.

INFP: Configured methods are copied as plain functions:
- `ClassName::methodName()` -> `ClassName__methodName()`
- output in `wp-runtime/copy/classes-statics/ClassName.php`
- parser replace static calls of `ClassName::methodName()` in copied code with `ClassName__methodName()` to break a dependency.


## Decision Outcome

- If eligible: add symbol to config, regenerate copies, add/update tests, run full suite.
- If not eligible: keep symbol disabled/commented in config with a short `why` reason.

Comment format and placement rules are documented in [config.md](config.md).
