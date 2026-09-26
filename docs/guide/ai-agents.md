# Instructions for AI agents

Add the following block to the testing section of your project's `AGENTS.md`.

```md
### Tests Runtime

This project uses `doiftrue/unitest-wp-copy` with `WP_Mock` for PHPUnit tests.

Before writing or changing tests:

1. Read `vendor/doiftrue/unitest-wp-copy/README.md` to understand the test runtime.
2. Check `vendor/doiftrue/unitest-wp-copy/SYMBOLS-INFO.md` for the WordPress functions and classes available in the runtime. Its first section lists runtime-adapted classes (like `\Unitest_WP_Copy\wpdb__Runtime`) with their public methods — use or extend them instead of WP_Mock.
3. Use `WP_Mock` when a runtime function listed as mockable needs to be mocked.
```
