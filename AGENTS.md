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
- Keep `wp-runtime/mocks/*` for manual runtime-adapted mocks only.
- When PHP code execution is needed, run it in container via `make php.run code='...'` (do not rely on local `php` CLI).


## Maintainer Docs

All maintainer instructions are centralized under `docs/`:
- At the start of each task in this repository, the agent must read all maintainer docs listed below before making changes.

- Runtime and bootstrap internals: `docs/runtime.md`
- Parser workflow: `docs/parser.md`
- Symbol suitability rules (pre-parser analysis): `docs/symbol-eligibility.md`
- Config model and merge rules: `docs/config.md`
- Test conventions: `docs/tests.md`
- Release workflow: `docs/releaser.md`


## Quick Commands

1. Install dependencies: `make composer.install`
2. Regenerate copies: `make parser.run`
3. Run tests: `make phpunit`
4. Run ad-hoc PHP code in container: `make php.run code='include "wp-core/wp-includes/version.php"; echo $wp_version, "\n";'`
