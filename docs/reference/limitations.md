# Limitations

Unitest WP Copy deliberately does not reproduce a complete WordPress
installation.

## Out of scope

- database queries and persistence;
- live HTTP requests and REST serving;
- the complete admin, frontend, login, cron, or AJAX lifecycle;
- filesystem-heavy plugin, theme, media, and update operations;
- all WordPress functions and classes;
- automatic compatibility for code that depends on hidden global state.

## Good use cases

Use the runtime for code centered on:

- formatting, escaping, sanitization, and parsing;
- deterministic URL and path helpers;
- hooks and shortcodes supported by the copied runtime;
- in-memory option behavior;
- SQL construction through the `wpdb` runtime adapter;
- in-memory REST route registration and dispatch;
- utility classes listed in `SYMBOLS-INFO.md`.

## When to use another test type

Use WordPress integration tests when correctness depends on real database rows,
schema behavior, core bootstrap ordering, installed plugins or themes, HTTP
serving, or filesystem operations.

A project can use both approaches: keep most business and transformation tests
fast with Unitest WP Copy, then cover a smaller number of infrastructure paths
with WordPress integration tests.
