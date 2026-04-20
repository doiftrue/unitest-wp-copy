# Parser Documentation

## Scope

This document describes parser behavior and parser-specific workflows.

Dependency documents:
- Config model and value formats: [config.md](config.md)
- Runtime constraints and available environment: [runtime.md](runtime.md)
- Test conventions: [tests.md](tests.md)


## Parser Role

Parser is a whitelist-based copier of selected WP symbols from `wp-core/` into `wp-runtime/copy/`.
It is not a dependency analyzer.

Run parser with:
- `make parser.run`
- or `php parser/run.php`


## What Parser Generates

`parser/run.php` builds `Updater`, which:
- reads configured WP source files/classes/methods;
- extracts selected top-level functions/classes;
- updates generated content after `// ------------------auto-generated---------------------`;
- wraps copied symbols with `function_exists`/`class_exists` guards;
- skips symbols whose `<since-version>` is higher than current `wp_version`;
- applies post-processing via `Extra_Replacer`:
  - option-call rewrites to `$GLOBALS['stub_wp_options']`;
  - static method call rewrite (`Class::method()` -> `Class__method()`).

If configured symbol is missing in source file, parser throws.


## Mandatory Dependency-Chain Rule

Before adding any function/class/static-method, validate full transitive dependency chain.

A symbol is allowed only if every dependency in its chain is:
- already available in current runtime (`wp-runtime/SYMBOLS-INFO.md`), or
- added in the same change and passes the same rule recursively.

Reject symbol if any chain segment requires unsupported runtime behavior (DB/full WP bootstrap/network, and similar).
Do not add unresolved dependencies "for later".
If option access is covered by `$GLOBALS['stub_wp_options']`, treat it as compatible with this runtime.


## Workflow: Add Symbols

1. Select candidate symbols from a concrete `wp-core` file.
2. Validate full dependency chain against current runtime symbols and constraints.
3. Update parser config for target WP line using rules from [config.md](config.md).
4. Regenerate copies: `make parser.run`.
5. Verify generated changes in `wp-runtime/copy/...`.
6. Add/update tests following [tests.md](tests.md).
7. Run full test suite: `make phpunit`.
8. If symbol remains incompatible, keep it disabled/commented in config with reason.


## Workflow: Auto-Mock Functions

Use auto-mock only for "original WP logic + handler injection":
- mark function config value as `'<since-version> mockable'` (see config doc);
- parser generates into `wp-runtime/copy/mockable/...`;
- function start includes `WP_Mock_Utils` handler check/call;
- add tests for:
  - default fallback behavior;
  - `WP_Mock::userFunction(...)` override behavior.

If runtime behavior must diverge from WP core, implement manual mock in `wp-runtime/mocks/*` instead.


## Workflow: Static Class Methods Compatibility

Use this only when:
- full class copy is not suitable;
- one static method is utility-like and dependency-safe.

Configured methods are copied as plain functions:
- `ClassName::methodName()` -> `ClassName__methodName()`
- output in `wp-runtime/copy/classes-statics/ClassName.php`
- parser replaces static calls in copied code.

Config format is defined in [config.md](config.md).


## Parser Code Style

When editing `parser/src/*`:
- keep implementation strict and simple;
- avoid defensive branches for invalid states that should fail fast;
- prefer direct logic with minimal branching.


## Constraints

- `wp-runtime/copy/` is generated output; avoid manual edits unless adaptation is intentional.
- Parser copies only configured symbols.
