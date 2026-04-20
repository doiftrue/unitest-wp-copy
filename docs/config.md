# Config Documentation

## Scope

This document defines parser config structure, merge model, and value formats.
Parser flow details are in [parser.md](parser.md).


## Config Layout

Base (latest supported WP line):
- `config/functions/<wp-source-file>.php`
- `config/symbols-moved.php`
- `config/classes.php`
- `config/static-methods.php`

Overrides for older WP lines:
- `config/<wp-line>/functions/<wp-source-file>.php`
- `config/<wp-line>/classes.php`
- `config/<wp-line>/static-methods.php`


## Merge Model (Base + Overrides)

- Parser reads WP version from `wp-core/wp-includes/version.php`.
- Parser derives line as `major.minor` (for example `6.8`).
- Parser loads base config from `config/*`.
- If `config/<major.minor>/` exists, it merges that override into base.

Merge rules:
- Nested symbol config (`functions/*`, `classes.php`):
  - scalar override value adds/replaces symbol metadata;
  - `false` on a symbol key removes inherited symbol.
- Flat config (`static-methods.php`):
  - scalar/array value adds/replaces file metadata;
  - `false` on a file key removes inherited file config.
- Versioned symbol moves (`symbols-moved.php`):
  - parser applies moves to inherited base config before version override merge;
  - move rule format is `moved_in/from/to` and parser decides target file by current WP line.


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

Classes:
- include: `'path/to/class-file.php' => [ 'ClassName' => '<since-version>' ]`
- remove inherited in override: `'path/to/class-file.php' => [ 'ClassName' => false ]`

Static methods compatibility:
- include:
  `'path/to/class-file.php' => [ 'class' => 'ClassName', 'methods' => [ 'methodName' => '' ] ]`
- remove inherited file config in override:
  `'path/to/class-file.php' => false`


## Rules for Disabled Symbols

- If a function/class is not suitable, keep it commented in config; do not delete it.
- In comments, list exact symbol names (for example `wp_get_theme`), not wildcard masks.
- Add a short reason when symbol is disabled because of an incompatible dependency chain. Add it as a comment starting with `// why:`.


## Related Docs

- Parser workflow: [parser.md](parser.md)
- Runtime constraints: [runtime.md](runtime.md)
- Test conventions for new symbols: [tests.md](tests.md)
