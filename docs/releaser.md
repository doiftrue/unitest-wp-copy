# Releaser Documentation

## Scope

This document describes multi-WP-line release workflow implemented by `releaser/release.sh`.

Dependencies:
- Parser regeneration flow: [parser.md](parser.md)
- Test conventions and execution: [tests.md](tests.md)


## Branch and Tag Model

- Main development branch: `main`
- One artifact branch per WP line: `wp-<major>.<minor>` (for example `wp-6.8`)
- Artifact branches contain runtime deliverables only:
  - `zero.php`
  - `README.md`
  - `SYMBOLS-INFO.md`
  - `VERSION` (full package version, identical to the release tag)
  - `wp-runtime/`
- Release tag format:
  - `<wp-major>.<wp-minor>.<script-major>.<script-minor>`
  - example: `6.8.2.8`


## Release Command

```bash
make release WP_LINE=6.8
# preview mode (no commit/tag/push):
make release WP_LINE=6.8 NOT_PUSH=1
```

Inputs:
- `WP_LINE` is required
- `RELEASE_TAG` is auto-generated as `<WP_LINE>.<script-major>.<script-minor>`, where
  `script-major.script-minor` are the last two parts of `VERSION`
  - example: if `WP_LINE=6.8` and `VERSION=7.0.2.8`, then tag is `6.8.2.8`
- optional `NOT_PUSH=1` runs all release steps but skips commit/tag/push


## Release Flow

1. Pin `wordpress/wordpress` to target WP line.
2. Regenerate runtime copies via parser.
3. Run full test suite.
4. Create or reuse git worktree for branch `wp-<line>`.
5. Copy `zero.php`, `README.md`, `SYMBOLS-INFO.md` and `wp-runtime/` into that worktree, then write the release tag to `VERSION`.
6. Commit on `wp-<line>`.
7. Create release tag.

No intermediate artifact directory is used.
