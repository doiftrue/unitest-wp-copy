# Available symbols

The runtime contains only reviewed WordPress functions and classes.

## Find a symbol

Open the file installed with the package:

```text
vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md
```

Use this file instead of the repository copy because it matches your installed
WordPress line and package version.

## Read the symbol type

| Section | How to use it |
| --- | --- |
| Runtime-adapted classes | Instantiate or extend the reduced class. |
| Custom-adapted symbols | Use the runtime-specific implementation directly. |
| Copied mockable functions | Use real behavior or override it with WP_Mock. |
| Copied functions and classes | Use the original copied behavior directly. |

For runtime-adapted classes, `[wp]` marks an unchanged WordPress method and
`[adapted]` marks runtime-specific behavior.

## If a symbol is missing

Do not add an ad-hoc global stub automatically. Prefer one of these:

1. Inject a project interface for database, network, or filesystem work.
2. Wrap the WordPress dependency in a small project adapter.
3. Use WP_Mock if the symbol is already listed as mockable.
4. Request a deterministic, dependency-safe symbol in the
   [issue tracker](https://github.com/doiftrue/unitest-wp-copy/issues).
