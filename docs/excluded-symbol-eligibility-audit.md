# Excluded Symbol Eligibility Audit

Audit target: bundled WordPress 7.0 and the current isolated runtime.
The 30 eligible functions identified by this audit have now been added.

## Inventory

The audit includes commented-out function entries and names in every
`Not suitable in isolated PHPUnit env` block under `config/functions/`.
Version moves/removals and ordinary comments are not exclusions.

- Function exclusion records: **1,637**
  - disabled inline: 21
  - rejected in blocks: 1,616
- Unique excluded function names: **1,635**
- Excluded static or instance methods: **0**
- Separately excluded classes: **9**

`wp_enqueue_stored_styles` and `wp_enqueue_block_style` each occur twice in
`config/functions/wp-includes/script-loader.php`, which explains the difference
between record and unique-name totals.

Function record classification:

| Verdict | Records |
| --- | ---: |
| Intentional custom mocks | 23 |
| Redundant cache compatibility implementations | 8 |
| Still ineligible | 1,606 |
| **Total** | **1,637** |

## Added Directly

These 15 functions were added through config, parser regeneration, and tests.
No parser implementation change was required.

| Function | Required handling | Evidence |
| --- | --- | --- |
| `wp_rel_ugc` | Regular copy; pure string transformation using available helpers. | `wp-core/wp-includes/formatting.php:3293`; `config/functions/wp-includes/formatting.php` |
| `wp_pre_kses_block_attributes` | Regular copy; its block/KSES/filter chain is available. | `wp-core/wp-includes/formatting.php:5220`; `config/functions/wp-includes/formatting.php` |
| `is_login` | Added as `mockable`; it is a request-state boundary. | `wp-core/wp-includes/load.php:1336`; `config/functions/wp-includes/load.php` |
| `wp_is_json_request` | Added as `mockable`; deterministic fallback from request headers. | `wp-core/wp-includes/load.php:1917`; `config/functions/wp-includes/load.php` |
| `wp_is_jsonp_request` | Added as `mockable`; `wp_check_jsonp_callback()` is already copied. | `wp-core/wp-includes/load.php:1936`; `config/functions/wp-includes/load.php` |
| `wp_is_xml_request` | Added as `mockable`; request-header/string checks only. | `wp-core/wp-includes/load.php:1982`; `config/functions/wp-includes/load.php` |
| `get_http_origin` | Added as `mockable`; lowest request-origin boundary. | `wp-core/wp-includes/http.php:425`; `config/functions/wp-includes/http.php` |
| `get_allowed_http_origins` | Regular copy; URL helpers and hooks are available. | `wp-core/wp-includes/http.php:448`; `config/functions/wp-includes/http.php` |
| `is_allowed_http_origin` | Regular copy after the two origin helpers above. | `wp-core/wp-includes/http.php:480`; `config/functions/wp-includes/http.php` |
| `get_default_feed` | Regular copy; WordPress 7.0 implementation is filter-only, so the current option-related rejection is stale. | `wp-core/wp-includes/feed.php:80`; `config/functions/wp-includes/feed.php` |
| `html_type_rss` | Regular copy; runtime `get_bloginfo()` resolves the existing `html_type` option. | `wp-core/wp-includes/feed.php:451`; `wp-runtime/custom-mocks/wp-includes/general-template.php:13`; `wp-runtime/boot-wp-options.php` |
| `wp_is_local_html_output` | Regular copy; the formerly missing `get_rest_url()` dependency now has a runtime adapter. | `wp-core/wp-includes/https-detection.php:173`; `wp-runtime/custom-mocks/wp-includes/rest-api.php:17` |
| `wp_is_connector_registered` | Regular WP 7.0 copy; registry class is already copied. | `wp-core/wp-includes/connectors.php:23`; `config/classes.php` |
| `wp_get_connector` | Regular WP 7.0 copy; in-memory registry dependency is available. | `wp-core/wp-includes/connectors.php:85`; `config/classes.php` |
| `wp_get_connectors` | Regular WP 7.0 copy; in-memory registry dependency is available. | `wp-core/wp-includes/connectors.php:150`; `config/classes.php` |

The five `mockable` functions have original-fallback and handler tests.
Connector tests use the standard `< 7.0.0` version guard.

## Added With Runtime Support

| Functions | Implemented support | Evidence |
| --- | --- | --- |
| `register_sidebars`, `register_sidebar`, `unregister_sidebar`, `is_registered_sidebar` | Initialized `$wp_registered_sidebars` in `wp-runtime/boot-wp-globals.php`; added teardown-safe global-state tests. | `wp-core/wp-includes/widgets.php:174-360`; `config/functions/wp-includes/widgets.php` |
| `register_block_pattern_category`, `unregister_block_pattern_category` | Copied `WP_Block_Pattern_Categories_Registry`; added wrapper and singleton-state tests. The full pattern registry remains unsuitable. | `wp-core/wp-includes/block-patterns.php:48-70`; `wp-core/wp-includes/class-wp-block-pattern-categories-registry.php`; `config/functions/wp-includes/block-patterns.php` |
| `get_allowed_block_template_part_areas`, `_filter_block_template_part_area` | Added guarded definitions for the five `WP_TEMPLATE_PART_AREA_*` file-level constants in an init part. | `wp-core/wp-includes/block-template-utils.php:10-23,73-120,267-285`; `config/functions/wp-includes/block-template-utils.php` |
| `is_email_address_unsafe`, `check_upload_mimes`, `upload_is_file_too_big`, `users_can_register_signup_filter`, `get_space_allowed` | Added deterministic ordinary and site-option defaults for the complete option dependency set. | `wp-core/wp-includes/ms-functions.php:398,2047,2140,2381,2614`; `wp-runtime/boot-wp-options.php`; `config/functions/wp-includes/ms-functions.php` |
| `wp_should_replace_insecure_home_url`, `wp_replace_insecure_home_url` | Added `https_migration_required => false` to runtime options and covered predicate/string replacement behavior. | `wp-core/wp-includes/https-migration.php:20-77`; `wp-runtime/boot-wp-options.php`; `config/functions/wp-includes/https-migration.php` |

## Keep as Custom Mocks

The following 23 core implementations must remain disabled because the project
intentionally supplies reduced isolated-runtime behavior:

- i18n: `__`, `_e`, `_x`, `_n`, `_nx`, `esc_html__`, `esc_html_e`,
  `esc_html_x`, `esc_attr__`, `esc_attr_e`, `esc_attr_x`
- options: `get_option`, `get_site_option`
- theme paths: `get_stylesheet_directory`, `get_stylesheet_directory_uri`,
  `get_template_directory`, `get_template_directory_uri`
- runtime adapters: `get_rest_url`, `wp_salt`, `get_bloginfo`,
  `wp_load_translations_early`, `switch_to_blog`, `restore_current_blog`

Sources: `config/functions/wp-includes/l10n.php`,
`config/functions/wp-includes/option.php`,
`config/functions/wp-includes/theme.php`,
`config/functions/wp-includes/rest-api.php`,
`config/functions/wp-includes/pluggable.php`,
`config/functions/wp-includes/general-template.php`,
`config/functions/wp-includes/load.php`,
`config/functions/wp-includes/ms-blogs.php`, and matching files under
`wp-runtime/custom-mocks/`.

## Redundant Compatibility Implementations

Do not add the eight rejected functions from
`config/functions/wp-includes/cache-compat.php`:

`wp_cache_add_multiple`, `wp_cache_set_multiple`, `wp_cache_get_multiple`,
`wp_cache_delete_multiple`, `wp_cache_flush_runtime`, `wp_cache_flush_group`,
`wp_cache_supports`, `wp_cache_switch_to_blog`.

Their canonical implementations are already configured from
`config/functions/wp-includes/cache.php`.

## Remaining Rejections

Every function in the cited rejection block remains ineligible after removing
the functions added above. Counts are exclusion records, not
necessarily unique names.

| Config source | Remaining | Shared blocker |
| --- | ---: | --- |
| `wp-admin/includes/screen.php` | 3 | User-meta/current-user runtime |
| `wp-includes/ai-client.php` | 1 | External AI provider registry |
| `wp-includes/author-template.php` | 16 | User/post/query runtime |
| `wp-includes/block-editor.php` | 10 | Editor, REST, theme, and request lifecycle |
| `wp-includes/block-patterns.php` | 8 | Full pattern registry, block hooks, filesystem, or HTTP |
| `wp-includes/block-template-utils.php` | 20 | Theme files, posts, terms, and block rendering |
| `wp-includes/block-template.php` | 9 | Theme/query/template request lifecycle |
| `wp-includes/blocks.php` | 26 | Metadata/filesystem and post/block rendering runtime |
| `wp-includes/bookmark-template.php` | 2 | Bookmark database chain |
| `wp-includes/bookmark.php` | 4 | Bookmark database chain |
| `wp-includes/cache.php` | 1 | Deprecated wrapper without additional test utility |
| `wp-includes/canonical.php` | 3 | Query/rewrite/redirect lifecycle and database |
| `wp-includes/capabilities.php` | 15 | Users, roles, and meta-capability database chain |
| `wp-includes/category-template.php` | 26 | Taxonomy, term, post, and Walker runtime |
| `wp-includes/category.php` | 10 | Term query/cache runtime |
| `wp-includes/comment-template.php` | 54 | Comment/post lookup, loop, database, or Walker runtime |
| `wp-includes/comment.php` | 66 | Comments/meta/database/cookies/network/cron |
| `wp-includes/connectors.php` | 9 | AI client, plugin/filesystem, REST, and option lifecycle |
| `wp-includes/cron.php` | 17 | Persistent cron option and remote spawning |
| `wp-includes/default-constants.php` | 1 | Deprecated process-global bootstrap side effects |
| `wp-includes/embed.php` | 30 | Embed/oEmbed, REST, HTTP, post, and template runtime |
| `wp-includes/error-protection.php` | 4 | Recovery/shutdown request lifecycle |
| `wp-includes/feed.php` | 25 | Query/post/comment/taxonomy/media/request/HTTP runtime |
| `wp-includes/formatting.php` | 9 | Deprecated APIs or option/filesystem/editor dependencies |
| `wp-includes/functions.php` | 119 | Bootstrap, database, filesystem, network, admin, or request lifecycle |
| `wp-includes/general-template.php` | 67 | Templates, auth, query/post loop, admin/editor/output lifecycle |
| `wp-includes/global-styles-and-settings.php` | 10 | Theme JSON, style engine, and filesystem |
| `wp-includes/http.php` | 13 | HTTP transport, network I/O, or header output |
| `wp-includes/https-detection.php` | 2 | Remote HTTP detection |
| `wp-includes/https-migration.php` | 2 | Persistent option mutation/install state |
| `wp-includes/kses.php` | 4 | User capability, upload path, or bootstrap-only hook work |
| `wp-includes/l10n.php` | 32 | Translation registry, filesystem, and user locale |
| `wp-includes/link-template.php` | 99 | Permalink/query/rewrite/admin/auth/multisite runtime |
| `wp-includes/load.php` | 28 | Bootstrap, database, global mutation, plugins, or filesystem |
| `wp-includes/media-template.php` | 3 | Media admin/rendering lifecycle |
| `wp-includes/media.php` | 56 | Attachment database/meta/filesystem/image/editor runtime |
| `wp-includes/meta.php` | 14 | Metadata database model |
| `wp-includes/ms-blogs.php` | 23 | Network/site database, options, cache, and users |
| `wp-includes/ms-functions.php` | 51 | Multisite database, users, sites, mail, filesystem, or bootstrap |
| `wp-includes/ms-load.php` | 9 | Multisite database/bootstrap |
| `wp-includes/ms-network.php` | 3 | Network database/cache |
| `wp-includes/ms-site.php` | 26 | Site database/meta/cache model |
| `wp-includes/nav-menu-template.php` | 3 | Nav-menu database, Walker, and query runtime |
| `wp-includes/nav-menu.php` | 20 | Terms, posts, queries, theme mods, and options |
| `wp-includes/option.php` | 37 | Database writes, transients, user settings, or network options |
| `wp-includes/pluggable.php` | 28 | Users, sessions, mail, headers, nonces, and database |
| `wp-includes/post-formats.php` | 6 | Post, term, and rewrite runtime |
| `wp-includes/post-template.php` | 37 | Post/query/meta/Walker loop runtime |
| `wp-includes/post-thumbnail-template.php` | 9 | Attachment/post metadata runtime |
| `wp-includes/post.php` | 114 | Post types, metadata, query, database, attachments, and filesystem |
| `wp-includes/query.php` | 47 | Global `WP_Query`, post loop, or database |
| `wp-includes/rest-api.php` | 34 | REST server, controllers, request/response, auth, and query chain |
| `wp-includes/revision.php` | 27 | Post/revision metadata and query runtime |
| `wp-includes/rewrite.php` | 10 | `WP_Rewrite`, persistent rewrite state, query, and database |
| `wp-includes/robots-template.php` | 2 | Query conditionals |
| `wp-includes/script-loader.php` | 46 | Script/style/editor bootstrap, filesystem, or output lifecycle |
| `wp-includes/script-modules.php` | 3 | Asset filesystem/editor lifecycle |
| `wp-includes/sitemaps.php` | 4 | Sitemap server, rewrite, query, and HTTP lifecycle |
| `wp-includes/style-engine.php` | 3 | Unavailable CSS/style-engine model |
| `wp-includes/taxonomy.php` | 71 | Term/query/meta/database/taxonomy-class chain |
| `wp-includes/theme.php` | 71 | `WP_Theme`, filesystem, customizer, theme mods, and media |
| `wp-includes/user.php` | 68 | `WP_User`, sessions, auth, mail, privacy, and database |
| `wp-includes/widgets.php` | 36 | Full widget/render/admin/persistence lifecycle |

The remaining table totals **1,606** records. The script-loader count contains
the two duplicate records identified in the inventory, so this bucket represents
**1,604 unique names**.

## Excluded Classes

No methods are disabled in `config/static-methods.php` or
`config/instance-methods.php`. The nine separately rejected classes remain
ineligible: `WP_Block_Patterns_Registry`, `Walker_Page`, `Walker_Category`,
`Walker_Nav_Menu`, `WP_Sitemaps_Posts`, `WP_Sitemaps_Taxonomies`,
`WP_Sitemaps_Users`, `WP_Sitemaps_Stylesheet`, and
`WP_Fatal_Error_Handler` (`config/classes.php`).
