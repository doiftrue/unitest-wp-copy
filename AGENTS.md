# AGENTS.md

## Purpose

This file is for coding agents maintaining this repository.
User-facing usage belongs to `README.md`.


## Agent Invariants

- Project code and code comments must be written in English.
- Treat `wp-runtime/copy/` as generated output. Do not manually edit generated logic.
- Do not use `worktrees/` for regular development. It is an artifact area for release branches only.
- When adding/updating copied symbols, update `config/*` first, then regenerate via parser, then rerun tests.
- Use `'mockable'` in function config only for "original WP logic + injected WP_Mock handler" behavior.
- Keep `wp-runtime/copy/mocks/*` for manual runtime-adapted mocks only.


## Maintainer Docs

All maintainer instructions are centralized under `_docs/`:

- Runtime and bootstrap internals: `_docs/runtime.md`
- Parser workflow: `_docs/parser.md`
- Config model and merge rules: `_docs/config.md`
- Test conventions: `_docs/tests.md`
- Release workflow: `_docs/releaser.md`


## Quick Commands

1. Install dependencies: `make composer.install`
2. Regenerate copies: `make parser.run`
3. Run tests: `make phpunit`
