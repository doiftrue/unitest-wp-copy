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
3. Update parser config for target WP line using rules from [config.md](config.md).
4. Regenerate copies: `make parser.run`.
5. Add/update tests following [tests.md](tests.md).
6. Run full test suite: `make phpunit`.
7. If symbol remains incompatible, keep it disabled/commented in config with reason.


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
