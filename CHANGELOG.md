# Changelog

This file records user-facing changes.

## Version format

Current release tags contain four numbers:

```text
<WordPress major>.<WordPress minor>.<runtime major>.<runtime minor>
```

For example, `7.0.2.11` targets WordPress 7.0 and contains runtime release
`2.11`. The same runtime release may be published for several WordPress lines,
such as `6.5.2.11`, `6.9.2.11`, and `7.0.2.11`.

The entries below are grouped by the runtime version (the last two numbers),
because its changes are shared by all WordPress-line tags that publish it.
Availability for a particular WordPress line can be checked in the repository
tags.

## 4.1 - 2026-09-08

### Added

- Added the WordPress abilities API and in-memory ability and ability-category
  registries.
- Added `WP_Block_Processor`, `WP_Block_Templates_Registry`, and block-template
  registration helpers.
- Added `WP_Font_Face` for in-memory font-face CSS generation.
- Added `make phpunit.all` to switch through and test every supported WordPress
  line.

### Changed

- Updated generated runtime copies and version overlays for the latest supported
  WordPress patch releases.

## 4.0 - 2026-09-06

### Added

- Added WordPress 7.1 as a supported line.
- Added an in-memory REST API runtime:
  - `WP_REST_Request` and `WP_REST_Controller`;
  - `WP_REST_Server__Runtime`, exposed through the compatible
    `WP_REST_Server` alias;
  - route registration and dispatch, validation, sanitization, OPTIONS,
    embedding, links, namespace indexes, and batch requests;
  - REST JSON Schema helpers;
  - authentication and capability adapters for `is_user_logged_in()` and
    `current_user_can()`;
  - `REST_API_VERSION` and the runtime-safe REST OPTIONS hook.
- Added parser support for imports required by generated instance-method
  traits.
- Added `WP_Filter_Sentinel` for WordPress 7.1.

### Changed

- Standardized runtime-adapted class names as the original WordPress class name
  plus `__Runtime`.
- Renamed runtime-adapter files to match their corresponding WordPress source
  filenames.

## 3.3 - 2026-08-04

### Added

- Added runtime-adapted classes, their public methods, properties, and method
  origins to `SYMBOLS-INFO.md`.

### Changed

- Expanded runtime-adapter documentation and clarified symbol eligibility
  documentation.

## 3.2 - 2026-07-30

### Fixed

- Fixed `WP_HTML_Open_Elements` test compatibility across supported WordPress
  lines.

## 3.1 - 2026-07-30

### Fixed

- Added release adaptations for older WordPress lines after the 3.0 symbol and
  class expansion.

## 3.0 - 2026-07-30

### Added

- Added more pure-PHP functions for HTTP origins, request type detection,
  uploads, feeds, sidebars, connectors, block patterns, block templates, and
  HTTPS migration.
- Added `get_rest_url()` and `rest_url()` runtime adapters.
- Added `get_bloginfo_rss()` and `bloginfo_rss()`.
- Added `WP_Connector_Registry` and `WP_Widget_Factory`.
- Added translation classes, `WP_Date_Query`, `WP_HTTP_Response`,
  `WP_REST_Response`, HTML API classes, Style Engine classes, Interactivity API
  classes, and other dependency-safe WordPress value and utility classes.

### Changed

- Re-audited existing functions and moved environment/runtime boundaries to
  the mockable function set where appropriate.
- Expanded option and global defaults needed by the newly copied symbols.

## 2.11 - 2026-07-06

### Added

- Added content and data helpers for bookmarks, categories, comments, metadata,
  navigation menus, options, revisions, users, feeds, widgets, connectors, and
  error protection.
- Added `WP_Meta_Query` and database-oriented query helpers.
- Added the non-querying `wpdb__Runtime` adapter for SQL-building tests.
- Added parser support for copying selected WordPress instance methods into
  traits used by runtime adapters.
- Added `esc_sql()`.

### Fixed

- Fixed tests for older supported WordPress lines.

## 2.10 - 2026-07-05

### Added

- Added block bindings classes and functions.
- Added block pattern, block style, block template, template hierarchy, and
  theme JSON path helpers.
- Added canonical, rewrite, and HTTPS detection helpers.

## 2.9 - 2026-07-04

### Added

- Added more compatibility, HTTP, and runtime-loading functions.

### Changed

- Added `VERSION` to release artifacts.

## 2.8 - 2026-07-03

### Added

- Added selected multisite blog, network, site, registration, and loading
  helpers.
- Added navigation menu, robots, category, post, comment, author, bookmark, and
  post-thumbnail template helpers.

## 2.7 - 2026-07-02

### Fixed

- Fixed multi-line release generation.

## 2.6 - 2026-07-02

### Changed

- Moved `SYMBOLS-INFO.md` to the repository and release root.

## 2.5 - 2026-07-02

This version existed in development but was not published as a release tag.

### Changed

- Changed `get_option()` so stored runtime options are no longer replaced by a
  WP_Mock handler; handlers apply only to options absent from the store.
- Reorganized generated and custom mock directories and symbol listing.
- Added more capability, pluggable, embed, and media helpers.

## 2.4 - 2026-07-01

### Changed

- Introduced the documented auto-mockable review policy.
- Reclassified functions whose results represent environment or runtime state
  as mockable.
- Standardized mockable test naming.

## 2.3 - 2026-07-01

### Added

- Added 62 functions from `wp-includes/blocks.php`.
- Added `WP_Block_Type_Registry`.
- Added `_navigation_markup()` and `is_avatar_comment_type()`.

## 2.2 - 2026-06-29

### Added

- Added `admin_url()` and mockable `get_admin_url()`.

## 2.1 - 2026-06-21

### Added

- Added all 18 WordPress shortcode functions.

### Fixed

- Fixed version-aware test skipping for WordPress 7.0.

## 2.0 - 2026-04-22

### Added

- Added common theme and site information functions, including `bloginfo()`,
  `get_bloginfo()`, `get_stylesheet()`, and `get_template()`.
- Added runtime adapters for theme and general-template state.

## 1.2 - 2026-04-22

### Added

- Added the `wp-line-extra` override mechanism for WordPress-line-specific
  runtime files.
- Added dedicated boot files for constants, globals, and options.
- Added support for WordPress lines 6.5 through 6.8.

## 1.1 - 2026-04-21

This version existed in development but was not published as a release tag.

### Changed

- Removed classes that were not independent in the isolated runtime:
  `WP_Block_Patterns_Registry`, `Walker_Page`, and `Walker_Category`.

## 0.27 - 2026-04-20

### Changed

- Consolidated runtime mocks under `wp-runtime/mocks/`.
- Added release generation for all supported WordPress lines.
- Improved generated symbol listing.

## 0.26 - 2026-04-19

### Added

- Introduced WordPress-line artifact branches and four-part release tags.
- Added release automation for WordPress 6.5, 6.6, 6.7, 6.8, and 6.9.

### Changed

- Renamed the bundled WordPress source directory to `wp-core`.
- Moved runtime deliverables under `wp-runtime`.

## Legacy 0.x releases

Before 0.26, releases used a single project version rather than separate
WordPress-line tags.

| Version | Date       | Main change                                                                                        |
|---------|------------|----------------------------------------------------------------------------------------------------|
| 0.25.0  | 2026-04-19 | Moved copied runtime files into `wp-runtime`.                                                      |
| 0.24.0  | 2026-04-18 | Switched the parser base to WordPress 6.9.                                                         |
| 0.23.5  | 2026-04-18 | Added the first WordPress-line-aware config mechanism; superseded by the releaser.                 |
| 0.23.4  | 2026-04-18 | Added per-symbol WordPress version metadata and mockable flags.                                    |
| 0.23.3  | 2026-04-18 | Added selected taxonomy functions.                                                                 |
| 0.23.2  | 2026-04-18 | Marked initial environment-dependent functions as mockable.                                        |
| 0.23.1  | 2026-04-14 | Added automatic parser generation for mockable functions.                                          |
| 0.23.0  | 2026-04-13 | Added `WP_Script_Modules` and more script-loader, REST, and post-format helpers.                   |
| 0.22.0  | 2026-04-12 | Added the WordPress scripts and styles runtime (`WP_Dependencies`, `WP_Scripts`, and `WP_Styles`). |
| 0.21.2  | 2026-04-10 | Split function configuration by WordPress source file.                                             |
| 0.21.1  | 2026-04-09 | Improved compatibility with older WP_Mock versions.                                                |
| 0.21.0  | 2026-04-09 | Added static-method compatibility copying and the `WP_Http::is_ip_address()` shim.                 |
| 0.18    | 2026-04-07 | Added WP_Mock handler injection and localization mocks.                                            |
| 0.17    | 2026-04-06 | Expanded documentation and test coverage.                                                          |
| 0.16    | 2026-02-13 | Removed the copied `is_admin()` implementation so tests can mock it.                               |
| 0.15    | 2025-10-26 | Added the `WP_ENVIRONMENT_TYPE` runtime constant.                                                  |
| 0.14    | 2025-10-21 | Added default local `home` and `siteurl` values and environment type setup.                        |
| 0.13    | 2025-10-21 | Added URL and email link-conversion functions.                                                     |
| 0.12    | 2025-10-21 | Moved symbol configuration into the parser directory.                                              |
| 0.11    | 2025-10-21 | Added class copying support.                                                                       |
| 0.10    | 2025-10-20 | Added cookie/SSL constants, `siteurl`, and broader tests.                                          |
| 0.9     | 2025-10-20 | Reduced KSES copying to runtime-safe functions and added KSES tests.                               |
| 0.8     | 2025-10-20 | Changed default `ABSPATH` and `WP_CONTENT_URL` values.                                             |
| 0.7     | 2025-10-20 | Moved option state to `$GLOBALS['stub_wp_options']`.                                               |
| 0.6     | 2025-10-19 | Moved WordPress and PHPUnit packages to development dependencies.                                  |
| 0.5     | 2025-10-19 | Added the initial PHPUnit suite and overridable copied functions.                                  |
| 0.4     | 2025-10-18 | Added environment configuration and refactored parser initialization.                              |
| 0.3     | 2025-08-03 | Renamed the initial config file and added repository packaging metadata.                           |
| 0.2     | 2025-07-27 | Added the first Composer-packaged runtime.                                                         |
