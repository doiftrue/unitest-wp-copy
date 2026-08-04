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
- reads configured WP source files, classes, methods;
- extracts selected top-level functions, classes;
- generates traits from configured instance methods for use by runtime-adapted classes;
- updates generated content after `// ------------------auto-generated---------------------`;
- wraps copied symbols with `function_exists` or `class_exists` guards;
- skips symbols whose `<since-version>` is higher than current `wp_version`;
- applies post-processing via `Source_Code_Replacer`:
  - static method call rewrite (`Class::method()` -> `Class__method()`).

If configured symbol is missing in source file, parser throws.

## Instance-method traits

`Instance_Methods_Trait_Copier` extracts configured methods from one named source class and generates a trait under `wp-runtime/copy/traits/` in the `Unitest_WP_Copy` namespace. Methods keep their original names, visibility, signatures, and bodies. Each method receives an annotation identifying the WordPress version it was copied from. Methods whose configured `since` version is higher than the current WordPress version are skipped.

The generated trait is internal implementation support and is not listed as a symbol in `SYMBOLS-INFO.md`; only its consuming runtime-adapted class is documented there. Its consumer must provide every property and excluded method referenced through `$this`. Methods requiring runtime-specific behavior must remain in the manual adapter rather than being configured for copying.


## Symbol list generation

`Symbols_Lister` regenerates `SYMBOLS-INFO.md` on every parser run. Sections:

- runtime-adapted classes — built by `Runtime_Classes_Doc_Builder` from classes declared in `wp-runtime/custom-mocks/*`, with public methods, public properties, and the class/file docblock summary; each method is marked `[wp]` when it comes from a used trait in `wp-runtime/copy/traits/`, or `[adapted]` when it is declared in the manual class;
- custom-adapted symbols — functions declared in `wp-runtime/custom-mocks/*`;
- copied mockable functions — `wp-runtime/copy/mockable/*`;
- copied functions and classes — everything else the parser copied.

A new manual class placed in `wp-runtime/custom-mocks/*` is documented automatically; no config or doc entry is required. To keep an entry useful, give the class file or the class itself a one-line docblock summary and mark runtime deviations in method docblocks.


## How `wp-line-extra` should be used

All inside `wp-runtime/wp-line-extra/<wp-line>/*` should override `wp-runtime/*` if relative path matches.
Example: `wp-runtime/wp-line-extra/6.8/init-parts/wp-includes/kses.php` overrides `wp-runtime/init-parts/wp-includes/kses.php` for the WP 6.8 line.


## Parser Code Style

When editing `parser/src/*`:
- keep implementation strict and simple;
- avoid defensive branches for invalid states that should fail fast;
- prefer direct logic with minimal branching.


## Constraints

- `wp-runtime/copy/` is generated output; avoid manual edits unless adaptation is intentional.
- Parser copies only configured symbols.
- Evaluate symbol suitability before configuring via [symbol-eligibility.md](symbol-eligibility.md).
