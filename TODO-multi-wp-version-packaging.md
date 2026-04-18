# TODO: Multi-WP Version Packaging (Base Config + Version Overrides)

## Decision

Use one canonical parser config for the latest supported WordPress line, plus small per-line override configs for older lines.

- Canonical config lives in top-level `config/`.
- Line-specific patches live in `config/<wp-line>/` (for example `config/6.7/`).
- Parser builds one effective config by merging base + line override.


## Why This Model

- Avoids full config duplication per WP line.
- Keeps latest WP support simple: update one canonical config first.
- Makes backport support explicit and reviewable (only deltas are stored).
- Supports symbol moves/removals between WP lines without branching parser logic.


## Config Layout

```text
config/
  WP-VERSION-LINE
  functions/
    wp-includes/formatting.php
    wp-includes/functions.php
    ...
  classes.php
  static-methods.php

  6.7/
    functions/
      wp-includes/formatting.php
      wp-includes/some-other-file.php
    classes.php
    static-methods.php
```

Rules:
- Top-level `config/*` is always for the newest supported WP line.
- `config/<wp-line>/*` contains only overrides for that line.
- Override files use the same structure as base config files.
- `config/WP-VERSION-LINE` stores the target WP minor line (`major.minor`) for the base config.
- Effective WP line is derived dynamically from parser runtime version (`\Parser\Config::$wp_version`).


## Merge Rules

Merge order:
1. Load base config from `config/*`.
2. Detect current WP line (`major.minor`) from `wordpress/wp-includes/version.php`.
3. If `config/<wp-line>/*` exists, apply it as patch on top of base config.

Patch semantics:
- `'symbol_name' => false` means "remove this symbol from effective config".
- `'symbol_name' => '6.5.0'` (or `'6.5.0 mockable'`) means add/replace symbol value.
- Nested arrays are merged recursively (`functions/*`, `static-methods.php` methods list, etc.).
- Empty nodes after deletion are removed.

Example (function introduced in WP 6.8, removed for 6.7 build):

```php
// config/6.7/functions/wp-includes/formatting.php
return [
	'new_68_function' => false,
];
```

Example (function moved between files):

```php
// config/6.7/functions/wp-includes/new-file.php
return [
	'moved_function' => false,
];

// config/6.7/functions/wp-includes/old-file.php
return [
	'moved_function' => '1.0.0',
];
```


## Base Config Lifecycle

Base config always tracks the newest supported WP line.

When a new WP line is released:
1. Update top-level `config/*` to the new latest line.
2. Update `config/WP-VERSION-LINE` to the new target WP minor line (`major.minor`).
3. Create `config/<previous-line>/` with only rollback/relocation overrides needed to reproduce previous line behavior.
4. Keep existing older line override folders as-is (update only if needed).


## Parser Behavior Requirements

- Parser must use current WP version from `wordpress/wp-includes/version.php`.
- It must derive version line as `major.minor` (for example `6.8`).
- It must merge base config with optional `config/<major.minor>/` overrides.
- Existing function-level `since-version` filter remains active after merge.
- If no line override exists, parser uses base config only.


## Operational Notes

- Do not copy full base config into `config/<wp-line>/`.
- Put only changed keys into line override files.
- Use `false` only where deletion from effective config is intended.
- For symbol moves, always do both sides:
  - remove from old location (`false`);
  - add to new location with target value.


## Migration Checklist

1. Keep canonical config aligned with latest WP line.
2. Keep `config/WP-VERSION-LINE` synced with the latest supported WP minor line for base config.
3. Add `config/<wp-line>/` patches only for differences from canonical.
4. Run parser and tests for each supported line during release process.
5. Ensure published artifacts for each line are generated from merged effective config.
