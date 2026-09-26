# FAQ and support

## Is this a complete WordPress test environment?

No. It is a selected in-memory runtime for unit tests. Use WordPress integration
tests when code requires the database, filesystem, network, or full bootstrap.

## Why not mock every WordPress function?

Real deterministic WordPress code catches formatting, sanitization, parsing, and
compatibility mistakes that a made-up mock return value cannot. Mock only the
environment boundary that the test needs to control.

## Why must Unitest WP Copy initialize before WP_Mock?

The runtime first defines copied WordPress functions. Mockable functions include
an injected WP_Mock handler path, so WP_Mock can control them after bootstrap.

## Can WP_Mock override every copied function?

No. Only functions listed in the mockable section of `SYMBOLS-INFO.md` support
that behavior. Regular copied functions intentionally keep their WordPress
implementation.

## Why does my option mock not run?

An option already present in `$GLOBALS['stub_wp_options']` or
`$GLOBALS['stub_wp_site_options']` takes priority. Change the stored property
directly, or use a missing option name for the WP_Mock handler.

## Which package version should I install?

Use the line matching the WordPress version supported by the plugin, such as
`doiftrue/unitest-wp-copy:6.9.*` for WordPress 6.9.

## Where can I report a problem?

Open an issue in the
[Unitest WP Copy repository](https://github.com/doiftrue/unitest-wp-copy/issues).
Include the package version, PHP version, smallest reproducible test, and full
error message.
