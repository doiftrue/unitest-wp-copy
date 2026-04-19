# Multi-WP Version Support Instruction

This directory contains the release workflow for supporting multiple WordPress version lines in one repository.

Supported lines are branches with `wp-` prefix (example: `wp-6.8`).


## HOW to Create release

```bash
make release WP_LINE=6.8
```

Input Variables:

- `WP_LINE` (required) - example `6.8`
- `RELEASE_TAG` is auto-generated as `<WP_LINE>.<last VERSION part #1>.<last VERSION part #2>`
  - example with `WP_LINE=6.8` and `VERSION=6.9.0.26`: `6.8.0.26`

## Branch and Tag Model

- Main development branch: `main`.
- One artifact branch per WP minor line: `wp-<major>.<minor>` (example: `wp-6.8`).
- Artifact branches store runtime deliverables only:
  - `zero.php`
  - `wp-runtime/`
- Release tag format:
  - `<wp-major>.<wp-minor>.<script-major>.<script-minor>`
  - example: `6.9.0.26`

Meaning of `6.9.0.26`:
- WordPress minor line: `6.9`
- Current script version: `0.26` (`0` is script major, `26` is script minor)

## Release Flow

Release is prepared from `main` and published to a line branch.

1. Pin `wordpress/wordpress` to the target line.
2. Run parser.
3. Run full tests.
4. Create/Reuse worktree for `wp-<line>`.
5. Copy `zero.php` and `wp-runtime/` directly into that worktree.
6. Commit changes on `wp-<line>`.
7. Create release tag.

No intermediate artifact directory is used.

This flow is implemented in:
- `_releaser/release.sh`
