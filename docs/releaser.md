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
  - `wp-runtime/`
- Release tag format:
  - `<wp-major>.<wp-minor>.<script-major>.<script-minor>`
  - example: `6.9.0.26`


## Release Command

```bash
make release WP_LINE=6.8
```

Inputs:
- `WP_LINE` is required
- `RELEASE_TAG` is auto-generated as `<WP_LINE>.<last VERSION part #1>.<last VERSION part #2>`
  - example: if `WP_LINE=6.8` and `VERSION=6.9.0.26`, then tag is `6.8.0.26`


## Release Flow

1. Pin `wordpress/wordpress` to target WP line.
2. Regenerate runtime copies via parser.
3. Run full test suite.
4. Create or reuse git worktree for branch `wp-<line>`.
5. Copy `zero.php` and `wp-runtime/` into that worktree.
6. Commit on `wp-<line>`.
7. Create release tag.

No intermediate artifact directory is used.
