# Maintainers

This site documents how to **use** Unitest WP Copy. Documentation for
**developing and maintaining** the package is kept in the repository, next to the
source it describes, so it stays accurate for contributors and coding agents.

## Where to look

- [`AGENTS.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/AGENTS.md) —
  invariants, quick commands, and the entry point for any change to the package.
- The `docs/` directory in the repository holds the detailed maintainer guides,
  meant to be read alongside the code:

  | Topic | Source |
  | --- | --- |
  | Runtime and bootstrap internals | [`docs/runtime.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/docs/runtime.md) |
  | Parser workflow | [`docs/parser.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/docs/parser.md) |
  | Symbol suitability rules | [`docs/symbol-eligibility.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/docs/symbol-eligibility.md) |
  | Config model and merge rules | [`docs/config.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/docs/config.md) |
  | Test conventions | [`docs/tests.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/docs/tests.md) |
  | Release workflow | [`docs/releaser.md`](https://github.com/doiftrue/unitest-wp-copy/blob/main/docs/releaser.md) |

## Workflow in brief

When adding or updating copied WordPress symbols, follow the order defined in the
source docs: update `config/*` first, regenerate copies via the parser
(`make parser.run`), then rerun tests (`make phpunit`). Treat `wp-runtime/copy/`
as generated output and never edit it by hand.

For anything beyond usage, start from `AGENTS.md` and the guides above rather than
this site.
