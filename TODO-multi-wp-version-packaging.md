# TODO: Packagist Strategy (One Package, Dist Branches Per WP Line)

## Decision (Locked)

Use this exact model for now:

- one public package on Packagist: `doiftrue/unitest-wp-copy`;
- one source repository for development;
- one development branch: `main`;
- publish-only branches per WP line: `dist/wp61`, `dist/wp62`, ..., `dist/wp69`;
- tags are created on `dist/wp*` commits and represent installable package versions.

No multiple long-lived development branches per WP version.


## Why this model

- keeps parser/runtime/tests in one place (`main`);
- avoids manual branch synchronization for shared code;
- stays compatible with free `packagist.org` requirements (VCS + tags);
- allows consumers to lock to one WP line (`~6.8.0`) and not jump to another line.


## Packagist Constraints (Important)

For free `packagist.org`, package versions come from Git tags in a public VCS repository.

- Packagist does not work like an artifact upload registry.
- To publish a new version, a tagged commit must exist in Git.
- Therefore release content for each WP line must exist in a branch and be tagged.


## Repository Layout (Development)

Development code lives in `main`:

```text
/
  _parser/
  src/
  tests/
  zero.php
  Makefile
  versions/
    wp-6.1/{config,copy}
    wp-6.2/{config,copy}
    ...
    wp-6.9/{config,copy}
```

Notes:

- `src`, `_parser`, and `tests` are shared.
- version-specific data is stored in `versions/wp-*/`.


## Publish Branch Layout

Each `dist/wpXY` branch contains only package-ready files for that WP line.

Example `dist/wp68` tree:

```text
/
  composer.json
  zero.php
  src/
  copy/      <- generated from versions/wp-6.8/copy
```

Not included in `dist/*`:

- `_parser/`
- `tests/`
- other `versions/wp-*` directories
- WordPress source mirror


## Versioning Convention

Use package versions aligned to WP line:

- WP 6.8 line uses package versions `6.8.x` (`6.8.0`, `6.8.1`, `6.8.2`, ...).
- WP 6.9 line uses package versions `6.9.x`.

This enables consumer constraints:

- project on WP 6.8: require `doiftrue/unitest-wp-copy:~6.8.0`.
- project on WP 6.9: require `doiftrue/unitest-wp-copy:~6.9.0`.


## Release Workflow (Per Line)

### 1. Develop in `main`

- make code/config/parser changes in `main`;
- regenerate target line copy (example: `WP_VERSION=6.8 make run.parser`);
- commit updates in `main`.

### 2. Test in `main` before release

- run shared test matrix against supported lines;
- minimally ensure target line passes;
- if shared code changed, run all lines.

### 3. Build publish tree for target line

For line `6.8`, CI creates package content:

- copy shared runtime files (`src`, `zero.php`, package metadata);
- map `versions/wp-6.8/copy` to root `copy/`;
- remove dev-only files.

### 4. Validate publish tree before tagging

In CI, validate package content:

- `composer validate`;
- smoke bootstrap test:
  - autoload package
  - run `\Unitest_WP_Copy\Bootstrap::init()`
  - execute several known functions/classes

### 5. Publish commit + tag

- push generated tree to `dist/wp68`;
- create tag `v6.8.N` on that exact commit;
- push tag.

Packagist reads the new tag and exposes it as a new installable version.


## CI Automation Plan

Create two workflow types.

### A) Continuous test workflow (`main`)

Trigger: push/PR to `main`.

- detect changed paths;
- run parser/test matrix as needed;
- fail on test failures or unexpected parser diffs.

### B) Release workflow (`workflow_dispatch`)

Inputs:

- `wp_line` (example: `6.8`);
- `release_type` (`patch` now, optional `minor/major` later);
- optional explicit `version` override.

Steps:

1. checkout `main`;
2. regenerate selected line;
3. build publish tree;
4. run validation/smoke checks;
5. update `dist/wpXY` branch;
6. create and push tag `vX.Y.Z`;
7. trigger Packagist update webhook (or wait for polling).


## Branch Policy

- `main`: only branch for development.
- `dist/wp*`: publish artifacts only; never edited manually.
- short-lived feature branches are allowed, then merged to `main`.


## Change Impact Rules

How to decide what to release:

1. If only `versions/wp-6.8/*` changed, release only `6.8.x`.
2. If shared runtime/parser changed (`src`, `_parser`, bootstrap behavior), re-test all lines and release affected lines.
3. If parser output changed for multiple lines, release each changed line separately.


## Consumer Documentation (to add in README)

- Explain that package versions are WP-line specific.
- Provide explicit constraints:
  - WP 6.8 -> `~6.8.0`
  - WP 6.9 -> `~6.9.0`
- State that users must not use broad constraints like `^6.0` if they want to stay on one WP line.


## Migration TODO (from current state)

1. Introduce `versions/wp-6.8/` as first line and move current `config/copy` there.
2. Make parser version-aware (`WP_VERSION` input).
3. Add bootstrap option to select active line in dev mode (env var).
4. Add initial `dist/wp68` generation script.
5. Add CI: test matrix + release workflow.
6. Register/update package on Packagist and configure webhook.
7. Backfill other lines (`6.1..6.9`) gradually.


## Open Technical Questions

- exact tag naming: `v6.8.3` vs `6.8.3` (both possible; choose one and keep consistent);
- whether `dist/wp*` history should be squash-like (clean) or full;
- how to auto-bump patch version safely in CI.


## Definition of Done

- `main` is the single source of truth for development.
- each WP line has reproducible publish branch `dist/wp*`.
- releases are created only by CI and tagged per line (`6.X.Y`).
- Packagist shows installable versions per WP line.
- consumers can reliably lock one WP line via Composer constraint.
