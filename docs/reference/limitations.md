# Limitations

Unitest WP Copy is for isolated unit tests, not WordPress integration tests.

## Included

- formatting, escaping, sanitization, and parsing;
- selected URL and path helpers;
- supported hooks and shortcodes;
- in-memory options;
- SQL building through the `wpdb` adapter;
- in-memory REST route dispatch;
- functions and classes listed in `SYMBOLS-INFO.md`.

## Not included

- database queries or persistence;
- live HTTP requests;
- a complete admin, frontend, cron, login, or AJAX lifecycle;
- filesystem-heavy plugin, theme, media, or update behavior;
- every WordPress function and class.

Use WordPress integration tests when behavior depends on a real database, full
bootstrap order, installed plugins or themes, HTTP serving, or filesystem state.
