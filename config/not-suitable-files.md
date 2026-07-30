# Analyzed Files Without Suitable Symbols

Paths are relative to `wp-core/`.

Entries below were reviewed using `docs/symbol-eligibility.md`. Re-review them when
WordPress changes the source or the runtime gains dependencies that remove the
recorded blockers.


## Function Files Without Active Functions

- `wp-includes/author-template.php`
- `wp-includes/bookmark-template.php`
- `wp-includes/comment-template.php`
- `wp-includes/cron.php`
- `wp-includes/media-template.php`
- `wp-includes/post-template.php`
- `wp-includes/post-thumbnail-template.php`
- `wp-includes/query.php`
- `wp-includes/style-engine.php`


## Class Review Coverage

The WordPress 7.0 review covered 756 named class declarations in 723 files. The
inventory includes declarations nested inside `class_exists()` guards.

- 70 classes are active in `config/classes.php`.
- 23 classes requiring a runtime-boundary decision are listed in
  `docs/symbol-eligibility-discussion.md`.
- The remaining 663 declarations are unsuitable for the isolated runtime and are
  covered below.

Directory entries cover every class-bearing PHP file below that directory unless
an explicit exception is listed.


### Bundled Libraries and Compatibility Implementations

These trees require namespace/import/file-level bootstrap preservation, large
cross-file dependency graphs, extension fallbacks, or network/filesystem behavior
that the class-body copier intentionally does not provide.

- `wp-includes/ID3/`
- `wp-includes/IXR/`
- `wp-includes/PHPMailer/`
- `wp-includes/Requests/`
- `wp-includes/SimplePie/`
- `wp-includes/Text/`
- `wp-includes/php-ai-client/`
- `wp-includes/sodium_compat/`


### Whole First-Party Subsystems

- `wp-admin/includes/` — all class-bearing files except active
  `wp-admin/includes/class-wp-screen.php`
- `wp-includes/customize/`
- `wp-includes/widgets/`
- `wp-includes/ai-client/`
- `wp-includes/rest-api/endpoints/` — except discussion candidate
  `wp-includes/rest-api/endpoints/class-wp-rest-controller.php`
- `wp-includes/rest-api/fields/`
- `wp-includes/rest-api/search/` — except discussion candidate
  `wp-includes/rest-api/search/class-wp-rest-search-handler.php`

These subsystems require admin/request lifecycle, DB-backed entities and queries,
live REST dispatch, network transports, or unavailable namespaced AI client
dependencies.


### POMO and Deprecated Protocol Classes

- `wp-includes/pomo/mo.php`
- `wp-includes/pomo/po.php`
- `wp-includes/pluggable-deprecated.php` — `wp_atom_server`
- `wp-includes/pomo/streams.php` — the unsuitable classes in this mixed file are
  `POMO_FileReader`, `POMO_CachedFileReader`, and `POMO_CachedIntFileReader`

`MO` and `PO` expose file import/export as central public behavior. The rejected
stream readers open or cache filesystem resources, and `wp_atom_server` requires
request parsing, authentication, database writes, and response output. The
in-memory POMO classes from `entry.php`, `plural-forms.php`, `translations.php`,
and `streams.php` are active in `config/classes.php`.


### Specialized First-Party Files

- `wp-includes/blocks/block.php`
- `wp-includes/blocks/navigation.php`
- `wp-includes/collaboration/class-wp-http-polling-sync-server.php`
- `wp-includes/collaboration/class-wp-sync-post-meta-storage.php`
- `wp-includes/fonts/class-wp-font-collection.php`
- `wp-includes/fonts/class-wp-font-library.php`
- `wp-includes/l10n/class-wp-translation-controller.php`
- `wp-includes/l10n/class-wp-translation-file-mo.php`
- `wp-includes/l10n/class-wp-translation-file-php.php`
- `wp-includes/l10n/class-wp-translation-file.php`
- `wp-includes/l10n/class-wp-translations.php`
- `wp-includes/sitemaps/class-wp-sitemaps-stylesheet.php`
- `wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php`
- `wp-includes/sitemaps/providers/class-wp-sitemaps-taxonomies.php`
- `wp-includes/sitemaps/providers/class-wp-sitemaps-users.php`

The blockers are DB-backed storage/query chains, file or remote loading, translation
file parsing, or HTTP/output lifecycle behavior.


### Top-Level `wp-includes` Files

- `wp-includes/atomlib.php`
- `wp-includes/class-avif-info.php`
- `wp-includes/class-json.php`
- `wp-includes/class-pop3.php`
- `wp-includes/class-requests.php`
- `wp-includes/class-snoopy.php`
- `wp-includes/class-walker-category-dropdown.php`
- `wp-includes/class-walker-category.php`
- `wp-includes/class-walker-comment.php`
- `wp-includes/class-walker-nav-menu.php`
- `wp-includes/class-walker-page-dropdown.php`
- `wp-includes/class-walker-page.php`
- `wp-includes/class-wp-admin-bar.php`
- `wp-includes/class-wp-ajax-response.php`
- `wp-includes/class-wp-application-passwords.php`
- `wp-includes/class-wp-classic-to-block-menu-converter.php`
- `wp-includes/class-wp-comment-query.php`
- `wp-includes/class-wp-comment.php`
- `wp-includes/class-wp-customize-control.php`
- `wp-includes/class-wp-customize-manager.php`
- `wp-includes/class-wp-customize-nav-menus.php`
- `wp-includes/class-wp-customize-panel.php`
- `wp-includes/class-wp-customize-section.php`
- `wp-includes/class-wp-customize-setting.php`
- `wp-includes/class-wp-customize-widgets.php`
- `wp-includes/class-wp-editor.php`
- `wp-includes/class-wp-embed.php`
- `wp-includes/class-wp-fatal-error-handler.php`
- `wp-includes/class-wp-feed-cache-transient.php`
- `wp-includes/class-wp-feed-cache.php`
- `wp-includes/class-wp-http-curl.php`
- `wp-includes/class-wp-http-ixr-client.php`
- `wp-includes/class-wp-http-requests-hooks.php`
- `wp-includes/class-wp-http-requests-response.php`
- `wp-includes/class-wp-http-streams.php`
- `wp-includes/class-wp-http.php`
- `wp-includes/class-wp-icons-registry.php`
- `wp-includes/class-wp-image-editor-gd.php`
- `wp-includes/class-wp-image-editor-imagick.php`
- `wp-includes/class-wp-image-editor.php`
- `wp-includes/class-wp-locale-switcher.php`
- `wp-includes/class-wp-metadata-lazyloader.php`
- `wp-includes/class-wp-navigation-fallback.php`
- `wp-includes/class-wp-network-query.php`
- `wp-includes/class-wp-network.php`
- `wp-includes/class-wp-oembed-controller.php`
- `wp-includes/class-wp-oembed.php`
- `wp-includes/class-wp-paused-extensions-storage.php`
- `wp-includes/class-wp-phpmailer.php`
- `wp-includes/class-wp-plugin-dependencies.php`
- `wp-includes/class-wp-post-type.php`
- `wp-includes/class-wp-post.php`
- `wp-includes/class-wp-query.php`
- `wp-includes/class-wp-recovery-mode-cookie-service.php`
- `wp-includes/class-wp-recovery-mode-email-service.php`
- `wp-includes/class-wp-recovery-mode-key-service.php`
- `wp-includes/class-wp-recovery-mode-link-service.php`
- `wp-includes/class-wp-recovery-mode.php`
- `wp-includes/class-wp-rewrite.php`
- `wp-includes/class-wp-role.php`
- `wp-includes/class-wp-roles.php`
- `wp-includes/class-wp-session-tokens.php`
- `wp-includes/class-wp-simplepie-file.php`
- `wp-includes/class-wp-simplepie-sanitize-kses.php`
- `wp-includes/class-wp-site-query.php`
- `wp-includes/class-wp-site.php`
- `wp-includes/class-wp-tax-query.php`
- `wp-includes/class-wp-taxonomy.php`
- `wp-includes/class-wp-term-query.php`
- `wp-includes/class-wp-term.php`
- `wp-includes/class-wp-text-diff-renderer-inline.php`
- `wp-includes/class-wp-text-diff-renderer-table.php`
- `wp-includes/class-wp-textdomain-registry.php`
- `wp-includes/class-wp-theme.php`
- `wp-includes/class-wp-user-meta-session-tokens.php`
- `wp-includes/class-wp-user-query.php`
- `wp-includes/class-wp-user-request.php`
- `wp-includes/class-wp-user.php`
- `wp-includes/class-wp-widget.php`
- `wp-includes/class-wp-xmlrpc-server.php`
- `wp-includes/class-wp.php`
- `wp-includes/class-wpdb.php`
- `wp-includes/rss.php`

These files are blocked by one or more of: DB-backed models and queries, live HTTP
or XML-RPC, filesystem/media handling, admin/customizer/request lifecycle, missing
file-level namespace/constants, or a key public contract that cannot run in the
isolated environment.
