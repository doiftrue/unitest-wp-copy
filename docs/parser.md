# Parser Documentation

## Scope

This document describes parser behavior and parser-specific implementation details.

Dependency documents:
- Config model and value formats: [config.md](config.md)


## Parser Role

Parser is a whitelist-based copier of selected WP symbols from `wp-core/` into `wp-runtime/copy/`.
It is not a dependency analyzer.

Run parser with:
- `make parser.run`


## What Parser Generates

`parser/run.php` builds `Updater`, which:
- reads configured WP source files/classes/methods;
- extracts selected top-level functions/classes;
- updates generated content after `// ------------------auto-generated---------------------`;
- wraps copied symbols with `function_exists`/`class_exists` guards;
- skips symbols whose `<since-version>` is higher than current `wp_version`;
- applies post-processing via `Source_Code_Replacer`:
  - option-call rewrites to `$GLOBALS['stub_wp_options']`;
  - static method call rewrite (`Class::method()` -> `Class__method()`).

If configured symbol is missing in source file, parser throws.


## How `wp-line-extra` should be used

All inside `wp-runtime/wp-line-extra/6.8/*` should override `wp-runtime/*` if relative path matches. 
Example: `wp-runtime/wp-line-extra/6.8/init-parts/wp-includes/kses.php` overrides `wp-runtime/init-parts/wp-includes/kses.php` for WP 6.8 line.


## Parser Code Style

When editing `parser/src/*`:
- keep implementation strict and simple;
- avoid defensive branches for invalid states that should fail fast;
- prefer direct logic with minimal branching.


## Constraints

- `wp-runtime/copy/` is generated output; avoid manual edits unless adaptation is intentional.
- Parser copies only configured symbols.
- Parser is not a dependency analyzer; evaluate symbol suitability via [symbol-eligibility.md](symbol-eligibility.md).
