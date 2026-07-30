# Config Documentation

## Scope

This document defines parser config structure, merge model, and value formats.
Parser flow details are in [parser.md](parser.md).


## Config Layout

Base (latest supported WP line):
- `config/functions/<wp-path>/<source-file>.php` (for example `config/functions/wp-includes/formatting.php`)
- `config/not-suitable-files.md`
- `config/symbols-moved.php`
- `config/symbols-removed.php`
- `config/classes.php`
- `config/static-methods.php`
- `config/instance-methods.php`

Overrides for older WP lines:
- `config/<wp-line>/functions/<wp-path>/<source-file>.php`
- `config/<wp-line>/classes.php`
- `config/<wp-line>/static-methods.php`
- `config/<wp-line>/instance-methods.php`


## Merge Model (Base + Overrides)

- Parser reads WP version from `wp-core/wp-includes/version.php`.
- Parser derives line as `major.minor` (for example `6.8`).
- Parser loads base config from `config/*`.
- If `config/<major.minor>/` exists, it merges that override into base.

Merge rules:
- Nested symbol config (`functions/*`, `classes.php`):
  - scalar override value adds/replaces symbol metadata;
  - `false` on a symbol key removes inherited symbol.
- Flat config (`static-methods.php`, `instance-methods.php`):
  - scalar/array value adds/replaces file metadata;
  - `false` on a file key removes inherited file config.
- Versioned symbol moves (`symbols-moved.php`):
  - parser applies moves to inherited base config before version override merge.
- Versioned symbol removals (`symbols-removed.php`):
  - parser applies removals after moves and before version override merge.


## Value Formats

Functions:
- regular: `'function_name' => '<since-version>'`
- mockable: `'function_name' => '<since-version> mockable'`
- remove inherited in override: `'function_name' => false`

Symbols moved (`config/symbols-moved.php`):
- structure:
  `'functions' => [ 'function_name' => [ 'moved_in' => '6.7', 'from' => 'wp-includes/functions.php', 'to' => 'wp-includes/load.php' ] ]`
- semantics:
  - `moved_in` means: symbol was moved from `from` to `to` in this WP line;
  - if current WP line is lower than `moved_in`, parser keeps function in `from`;
  - otherwise parser keeps function in `to`.

Symbols removed (`config/symbols-removed.php`):
- structure:
  `'functions' => [ 'function_name' => [ 'removed_in' => '7.0', 'file' => 'wp-includes/compat.php' ] ]`
- semantics:
  - `removed_in` means: symbol was removed from WordPress core in this WP line;
  - if current WP line is lower than `removed_in`, parser keeps the function configured in `file`;
  - otherwise parser removes the function from the inherited config.

Classes:
- include: `'path/to/class-file.php' => [ 'ClassName' => '<since-version>' ]`
- remove inherited in override: `'path/to/class-file.php' => [ 'ClassName' => false ]`

Static methods compatibility:
- include:
  `'path/to/class-file.php' => [ 'class' => 'ClassName', 'methods' => [ 'methodName' => '' ] ]`
- remove inherited file config in override:
  `'path/to/class-file.php' => false`

Instance methods copied into a trait:
- include:
  ```php
  'path/to/class-file.php' => [
      'class'   => 'SourceClass',
      'trait'   => '{SourceClass}__Copied_Methods',
      'methods' => [ 'methodName' => '<since-version>' ],
  ]
  ```
- `class` selects the source class in the WordPress file;
- `trait` defines the generated trait and output filename;
- `methods` maps original instance method names to the WordPress version where each method was introduced;


## Rules for Disabled Symbols

- If a function/class is not suitable, keep it commented in config; do not delete it.
- In comments, list exact symbol names (for example `wp_get_theme`), not wildcard masks.
- Keep the reason short and specific to the incompatible dependency/runtime chain.

A symbol in a config file can be in one of three states:

1. **Active** — present as an array key:
   ```php
   'trailingslashit' => '1.2.0',
   ```

2. **Disabled inline** — commented-out array entry (has custom mock or is temporarily disabled):
   ```php
   // 'get_stylesheet_directory' => '', // why: custom mock
   ```
   Symbol has a manual mock in `wp-runtime/custom-mocks/*`. Stays inside the array region for visibility.

3. **Rejected in block** — listed in a dedicated `/* ... */` block comment after the array (ineligible for this runtime):
   ```php
   /*
   Not suitable in isolated PHPUnit env:    

   esc_sql           // why: depends on $wpdb
   sanitize_option   // why: depends on $wpdb + options/roles/i18n runtime chain
   */
   ```
   symbol was analyzed and found permanently ineligible for this runtime. Grouped at file end to keep the active array clean.


## Files Without Active Functions

`config/not-suitable-files.md` records WordPress source files that were analyzed
but currently contribute no active function to parser config.

- Store paths relative to `wp-core/`, for example `wp-includes/query.php`.
- Add a path only after reviewing all relevant top-level functions in the source
  file using [symbol-eligibility.md](symbol-eligibility.md).
- Keep rejection reasons in the corresponding
  `config/functions/<wp-path>/<source-file>.php` file when that config file
  exists.
- Check this registry before starting a file-level suitability review, so an
  unchanged file and dependency chain are not analyzed repeatedly.
- Re-review an entry when WordPress changes the source file or the project gains
  dependencies that can remove its recorded blockers.
- Remove the path as soon as at least one function from the file becomes active
  in parser config.
