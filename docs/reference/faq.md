# FAQ

## Is this a complete WordPress test environment?

No. It is an in-memory runtime for selected WordPress code.

## Why not mock every WordPress function?

Real formatting, sanitization, and parsing code catches mistakes that fixed mock
values cannot.

## Why is my `get_option()` mock ignored?

Values configured through `WP_Options::set()` are checked before WP_Mock handlers.
Change the stored value or mock an option name that is absent from the store.
See [Options](/reference/options) for the complete lookup order and examples.

## Which package version should I install?

Match the WordPress line. See [WordPress versions](/guide/wp-versions)
for the supported lines and Composer constraints.

## Why must "Unitest WP Copy" load before WP_Mock?

The runtime defines the WordPress functions first. Mockable functions then use
WP_Mock handlers when a test registers one.

## Can WP_Mock replace every copied function?

No. Only functions in the **Copied mockable functions** section of
`SYMBOLS-INFO.md` can be replaced.

## Where do I report a problem?

Open an [issue](https://github.com/doiftrue/unitest-wp-copy/issues) with the
package version, PHP version, smallest reproducible test, and full error message.
