# Available symbols

Unitest WP Copy is whitelist-based. A function or class is available only when
it has been reviewed for isolated execution and included in the selected package
release.

## Symbol index

The generated
[`SYMBOLS-INFO.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/SYMBOLS-INFO.md)
is the authoritative index. When the package is installed, use the copy in
`vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md` because it matches the exact
runtime version under test.

## Index sections

| Section | Meaning |
| --- | --- |
| Runtime-adapted classes | Reduced WordPress-compatible classes with copied and adapted methods. |
| Custom-adapted symbols | Manual implementations designed for the isolated runtime. |
| Copied mockable functions | Original WordPress logic that can be overridden through WP_Mock. |
| Copied functions and classes | Original WordPress code used directly by tests. |

For runtime-adapted classes, `[wp]` identifies an unchanged copied WordPress
method and `[adapted]` identifies runtime-specific behavior.

## If a symbol is missing

A missing symbol is not automatically safe to stub globally. First decide what
the production code needs:

- inject a project interface for database, network, or filesystem work;
- wrap the WordPress boundary in a small project adapter;
- use WP_Mock when the function is already listed as mockable;
- open an issue if a deterministic WordPress symbol appears suitable for this
  runtime.

Do not assume that a function exists merely because a related WordPress function
is included.
