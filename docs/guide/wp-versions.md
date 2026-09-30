# WordPress versions

Each release line of this package targets one WordPress major.minor line.
For example, `doiftrue/unitest-wp-copy:7.1.*` contains selected code copied
from WordPress 7.1 and its runtime compatibility files.

## Supported WP Versions

Install the package line that matches the WordPress line you need to test:

| WordPress line | Composer constraint |
| --- | --- |
| 7.1 | `doiftrue/unitest-wp-copy:7.1.*` |
| 7.0 | `doiftrue/unitest-wp-copy:7.0.*` |
| 6.9 | `doiftrue/unitest-wp-copy:6.9.*` |
| 6.8 | `doiftrue/unitest-wp-copy:6.8.*` |
| 6.7 | `doiftrue/unitest-wp-copy:6.7.*` |
| 6.6 | `doiftrue/unitest-wp-copy:6.6.*` |
| 6.5 | `doiftrue/unitest-wp-copy:6.5.*` |

::: info
WordPress versions before 6.5 are not supported.
:::

For example, for a plugin tested with WordPress 6.9:

```bash
composer require --dev doiftrue/unitest-wp-copy:6.9.*
composer require --dev 10up/wp_mock
```

## What the version means

The first two numbers select the target WordPress line. The package also has
its own release revision, so a complete tag such as `7.0.2.8` means:

- `7.0` — code and runtime compatibility for WordPress 7.0 - `<major>.<minor>`.
- `2.8` — the release revision of this package for that line - `<major>.<minor>`.

Usage examples in your composer.json:
- `7.0.2.8` - pin one exact release.
- `7.0.2.*` - allow conservative updates starting from this build (small fixes).
- `7.0.*` - allow any update of this package (major changes may affect existing tests).

## Supporting more than one WordPress line

One installed dependency set contains one WordPress line. If your plugin
supports several WordPress lines, run its test suite in a CI matrix and install
the matching package constraint in each job. For example, test one job with
`6.5.*` and another with `7.1.*`.

