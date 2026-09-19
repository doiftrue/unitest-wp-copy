# Symbol Eligibility Last Audit

**Audit date:** 2026-09-19

**Baseline commit:** `b3f484b`

**Target:** bundled `wp-core/`, reporting WordPress `7.1`.

**Result:** the confirmed safe recommendations below were implemented. Remaining
incompatible active branches and public-contract decisions are intentionally
tracked separately rather than being silently removed or adapted.

## Scope and evidence

Read all six maintainer documents, runtime bootstrap/adapters, parser/config
flow, rejection/discussion registries, and release scripts. Inventoried PHP
function and named class declarations throughout bundled core with PHP-Parser;
checked named dependencies of every effective copied function and class;
checked literal callback registrations and adapter internal method references;
reviewed option reads, mock boundaries, candidate implementations and blockers.
Significant failures were reproduced in fresh container processes.

| Inventory | Result |
| --- | ---: |
| Effective configured functions | 846 |
| Regular / auto-mockable functions | 747 / 99 |
| Source files contributing active functions | 68 |
| Whole copied classes | 78 in 75 files |
| Manual runtime classes | 2 |
| Static methods copied as functions | 2 |
| Instance methods configured for adapter traits | 31: 7 database, 24 REST |
| Bundled core function declarations | 4,385 |
| Bundled core named class declarations | 758 in 725 files |

Declaration counts include guarded/nested declarations and bundled libraries;
they are not counts of eligible APIs. Config counts include compatibility
functions even when PHP supplies their implementation. Version `7.1` was
normalized to `7.1.0`, matching the parser's version comparison.

The [audit inventory](symbol-eligibility-audit-inventory.md) records every active
function and class, current configuration, named-dependency findings, and
dedicated-test-name checks.

Verification:

- `make phpunit`: **1,097 tests, 2,435 assertions, passed**.
- `make parser.run QUIET=1`: succeeded after the config-first changes.
- PHPUnit with `--random-order-seed=20260919`: **1,097 tests, 2,435
  assertions, passed**.
- The supported 6.5–7.0 lines passed their PHPUnit runs with their expected
  version skips; 7.1 passed after the runtime was restored from the lock file.
- Fresh-runtime probes reproduced the reported date, HTML, excerpt, uninstall,
  Global Styles, capability, metadata, script and multisite failures. I/O-policy
  findings were established by source inspection; the Make failure was observed
  directly in the shell.
- Both runtime adapters provide every literal `$this->method()` target found
  in their copied/manual methods.

Limits: this is a whole-project static inventory with targeted behavioral
verification, not exhaustive execution of every branch. Dynamic callbacks,
object methods, includes and globals require more than name-existence checks.
Conclusions concern bundled source, not independently verified upstream release
contents. No release/publishing commands were run.

## Confirmed findings

### F01 — HTML named entities fail after normal bootstrap (high)

`WP_HTML_Decoder::decode_attribute('&copy;')` throws
`Call to a member function read_token() on null`. Reading an HTML tag attribute
containing `a&amp;b` through `WP_HTML_Tag_Processor` fails the same way.

The missing global `$html5_named_character_references` is initialized by
`wp-includes/html-api/html5-named-character-references.php`. The runtime copies
the decoder and `WP_Token_Map`, but does not initialize this data. This affects
HTML API consumers, not only direct decoder calls.
`WP_HTML_Decoder__Test::test__not_independent_entity_parsing_path()` explicitly
expects the error, concealing the incompatibility in the green suite.

**Action:** copy/load the original data initialization after `WP_Token_Map`
is available, respecting version-specific source layouts. Replace the expected
error with positive named-entity tests. Do not edit generated classes manually.

### F02 — `wp_date()` fails in a clean runtime (high)

`wp_date('Y-m-d', 0)` throws
`Call to undefined function wp_maybe_decline_date()`. This dependency is rejected
in config even though the locale object, translation adapters and string helpers
it needs are available.

The default suite masks the problem: `test__number_format_i18n()` replaces
`$wp_locale` with an object containing only `number_format`, without restoring
it. Later `wp_date()` calls bypass the broken localized branch.

**Action:** copy `wp_maybe_decline_date` as regular, restore locale state in tests,
and test dates against a real bootstrapped `WP_Locale`. Loading the original
dependency in the audit process changed the reproduction result to `1970-01-01`.

### F03 — Active functions have unsupported useful branches (high)

| Function / reproduction | Confirmed blocker | Disposition |
| --- | --- | --- |
| `wp_trim_excerpt()` | Missing `get_post()`; also content/block rendering dependencies | Reject unchanged copy; an excerpt adapter needs a separate contract |
| `register_uninstall_hook('/plugins/a/main.php', '__return_null')` | Missing `update_option()` | Reject unchanged copy; persistence is outside scope |
| `wp_filter_global_styles_post('{"isGlobalStylesUserThemeJSON":true,"version":3}')` | Missing `WP_Theme_JSON` | Reject until Theme JSON is supported |
| `get_allowed_mime_types(1)` | Missing `user_can()` | Needs an explicit capability adapter or supported-surface decision |
| `get_current_network_id()` with `MULTISITE=true` | Missing `get_network()`, potentially `get_main_network_id()` | Needs network-state adaptation or a single-site restriction; current mockable fallback is unsafe in multisite |

The current excerpt test supplies nonempty text; the uninstall test supplies an
invalid object callback; the Global Styles test supplies non-JSON CSS. These
inputs avoid the principal behavior and do not prove eligibility. Adding
`mockable` does not fix an unsupported default implementation.

### F04 — `register_meta()` installs an unavailable callback (high)

This combination of supported APIs throws a `TypeError` for invalid callback
`filter_default_metadata`:

```php
register_meta( 'post', 'audit_default', [
    'type' => 'string', 'single' => true, 'default' => 'hello',
] );
get_metadata_default( 'post', 1, 'audit_default', true );
```

Config rejects the callback because its `get_object_subtype()` dependency needs
live object lookup. A direct-call-only dependency scan misses its registration.

**Action:** define the supported subtype/default-metadata contract before
adapting the callback or registration. Do not copy the missing callback blindly.
`register_post_meta` and `register_term_meta` inherit this problem and are not
unconditional safe additions. Their unregister wrappers are lightweight, but
do not complete registration support.

### F05 — Whole copied classes expose partial contracts (medium)

- `WP_Scripts::print_translations()` fails with missing `load_script_textdomain()`
  after translations are registered for a script.
- `WP_Script_Modules::add_hooks()` fails with missing `wp_is_block_theme()`.
  Its queued-module translation path additionally calls unavailable
  `load_script_module_textdomain()`.
- `WP_Screen` exposes unavailable user/admin/meta-box dependencies. Its expected
  error test documents a limitation, not whole-class eligibility.
- `WP_Sitemaps_Renderer` combines useful in-memory XML construction with live
  output/headers and a `wp_die()` extension-failure path.

**Action:** explicitly document partial capabilities or introduce adapters with
defined omissions. Do not add full theme/filesystem translation subsystems just
to silence these failures. Describing these whole classes as dependency-complete
contradicts the current eligibility policy.

### F06 — Option defaults are incomplete (medium)

`blog_public` is absent from the boot store, but three active functions read it:
`wp_robots_noindex`, `wp_robots_no_robots`, `wp_robots_max_image_preview_large`.
Consequently the fresh runtime implicitly behaves as a private site:

```text
wp_robots_noindex([])                 => {noindex: true, nofollow: true}
wp_robots_max_image_preview_large([])  => []
```

Add an intentional `blog_public` default and cover public/private cases.
The missing `uninstall_plugins` option belongs to F03; adding a value alone
cannot fix its unsupported write path.

Other literal option reads in configured functions/classes resolve to the boot
stores, as do the REST adapter's index options. Dynamic image-size reads require
care: the four built-in sizes have width/height/crop values, but arbitrary names
introduced through `intermediate_image_sizes` need complete size data or option
fixtures. Arbitrary dynamic option access is not automatically eligible.

### F07 — Existing I/O behavior needs a policy decision (medium)

`wp_http_validate_url()` calls `gethostbyname()` for external hostnames, so it is
not an in-memory validator. This was established by source inspection without
issuing a DNS request.

Existing functions also read files (`wp_json_file_decode`, `get_file_data`,
`wp_get_image_mime`), inspect theme paths (`get_theme_file_uri`,
`get_theme_file_path`, `get_locale_stylesheet_uri`), or inspect filesystem-backed
streams (`path_is_absolute`). The three mockable REST diagnostic handlers emit
HTTP headers in their original implementations.

**Action:** clarify whether caller-owned fixture files and header emitters are
intentional exceptions; treat DNS/network access separately. A mockable marker
does not make these defaults pure. Avoid removals without compatibility review.

### F08 — `make php.run` is broken (medium)

The documented command fails before Docker starts with
`/bin/sh: 2: @mkdir: not found`. `php_run` starts with `@mkdir`, but `php.run`
expands it inside another shell command, where `@` is not Make's silencing prefix.

The nonempty-code check also interpolates PHP into a double-quoted shell
condition; quoted PHP strings with spaces produced `[: ...: unexpected operator`.
Separate command bodies from Make recipe silencing and validate input without
shell-interpolating PHP. Audit probes used `make php.run` in Docker with a
temporary Makefile override of the macro; no host PHP interpreter was used.

### F09 — Tests depend on execution order (medium)

Random seed `20260919` exposed:

1. `Instance_Methods_Trait_Copier__Test::test__generate_content__throws_for_missing_configured_method`:
   its handcrafted item omits `imports`, which `generate_content()` reads before
   checking missing methods. Normal `get_items()` provides the key: this is a
   malformed fixture, not a demonstrated failure of normal generation.
2. `formatting__Test::test__links_add_base`: calls `_links_add_base()` with a
   string and extra arguments instead of the regex-match array it expects,
   and relies on `$_links_add_base` left by another test.

The locale leak in F02 is another verified false-positive cause. Tests also
clear all hooks without restoring the runtime-installed REST OPTIONS hook;
fresh-instance integration checks should cover that state boundary.

Exact-name checks found **42** functions without a dedicated
`test__<function>()` name and **27** mockable functions without a dedicated
`test__<function>__mockable_handler()` name. These are naming/coverage-review
findings, not claims that all these behaviors are untested: some tests combine
symbols or include override assertions in the fallback test (`wp_nonce_tick`,
for example). The inventory identifies all affected entries.

## Auto-Mockable decisions

The review covers 99 existing mockable and 747 regular entries. Prefer the lowest
shared boundary, retaining transforms, mutators and filter-controlled helpers
as regular functions.

### Recommended new boundaries in the active set

| Functions | Reason |
| --- | --- |
| `current_datetime` | Reads the real clock directly; mocking `current_time` cannot control it |
| `wp_parse_auth_cookie` | Default path reads request cookies; retain explicit-cookie parsing as fallback |
| `wp_has_ability`, `wp_get_ability` | Singleton registry reads otherwise require lifecycle registration state |
| `wp_has_ability_category`, `wp_get_ability_category`, `wp_get_ability_categories` | Corresponding category registry boundaries |
| `shortcode_exists` | Lowest existence check over the shortcode registry; keep callers regular |
| `force_ssl_content` | Shared configuration getter/setter, analogous to `force_ssl_admin` |
| `wp_high_priority_element_flag` | Shared process-static media-priority flag |

All ten recommendations need original-fallback and separate handler tests.
Preserve declared return types, initialization timing, and getter/setter behavior;
the marker must not silently change the WordPress contract.

### Keep regular or avoid redundant markers

- Keep argument-driven transforms, schema helpers, registry mutations and cache
  writes regular. Keep `wp_suspend_cache_invalidation` regular: it mutates state
  on every call rather than providing a getter-only invocation.
- Keep `home_url`, `site_url`, `admin_url`, `has_image_size`, and
  `wp_is_application_passwords_available` regular; their lower providers already
  supply the relevant seam.
- Keep filter-controlled functions such as `get_default_feed`,
  `get_allowed_block_types`, `wp_lazy_loading_enabled`,
  `wp_omit_loading_attr_threshold`, and `wp_sitemaps_get_max_urls` regular.
- On bundled 7.1, `wp_get_abilities` has a result filter. Reconsider separately
  for older versions lacking that filter.
- Keep `rest_get_server` regular for now: `wp_rest_server_class` supports
  replacing the server implementation and the default adapter is usable.
- `wp_cache_get` is a prospective boundary, but its `&$found` output must survive
  handler dispatch. Current injection uses `func_get_args()` by value. Resolve
  and test reference-output semantics before adding its marker.

Existing markers on `post_type_exists`, `is_post_type_hierarchical`,
`is_post_type_viewable`, `is_post_status_viewable`, `is_taxonomy_hierarchical`,
`is_taxonomy_viewable`, and `is_protected_meta` are redundant under the current
lowest-boundary/filter policy. Removing them would break consumers that already
mock them; treat removal as an API compatibility decision, not routine cleanup.

`get_current_network_id` is an eligibility problem before a marker problem (F03).
`wp_rand` has a rare entropy-failure fallback through unavailable
`get_transient`/`set_transient`; ordinary `random_int()` execution passes, but
the complete failure path is unsupported. Document/adapt this boundary without
introducing DB-backed transient functions.

## Confirmed additions shortlist

These **22 functions** have suitable useful implementations in the current
dependency model. All were added config-first and regenerated; regression tests
cover their public behavior. This is not an assertion that no other eligible
functions exist.

| Source in `wp-includes/` | Functions | Mode and reason |
| --- | --- | --- |
| `functions.php` | `wp_maybe_decline_date` | Regular; existing locale/translation chain, fixes F02 |
| `functions.php` | `wp_extract_urls`, `wp_removable_query_args` | Regular; strings/arrays/filter; lack of internal consumers is not incompatibility |
| `functions.php` | `get_weekstartend` | Regular; configured `start_of_week` and date constants; control PHP timezone in tests |
| `functions.php` | `wp_timezone_override_offset` | Mockable candidate: timezone configuration and current-date DST offset |
| `functions.php` | `wp_is_serving_rest_request` | Mockable constant-backed environment predicate; does not serve a request |
| `functions.php` | `is_php_version_compatible` | Mockable PHP-version boundary |
| `functions.php` | `is_wp_version_compatible` | Regular wrapper over adapted/mockable `wp_get_wp_version` |
| `style-engine.php` | `wp_style_engine_get_styles`, `wp_style_engine_get_stylesheet_from_css_rules`, `wp_style_engine_get_stylesheet_from_context` | Regular; five required Style Engine classes are available; restore stores in tests |
| `cron.php` | `wp_get_schedules` | Regular; constants, translation and `cron_schedules` filter, no persisted events |
| `block-patterns.php` | `wp_normalize_remote_block_pattern` | Regular; renames two array keys, no registry or network |
| `comment.php` | `wp_should_disable_pings_for_environment` | Regular; existing environment boundary plus filter; 7.1 |
| `media.php` | `wp_get_image_encode_quality` | Regular; numeric normalization and quality filters, no image editor or files; 7.1 |
| `media.php` | `wp_get_chromium_major_version` | Mockable request-header boundary; 7.1 |
| `media.php` | `get_taxonomies_for_attachments` | Regular; existing taxonomy registry metadata, no attachment queries |
| `connectors.php` | `wp_connectors_parse_application_password_credentials` | Regular; string splitting/trimming, no authentication or option lookup; 7.1 |
| `view-config.php` | `wp_get_entity_view_config_hook_name` | Regular; lowercase string composition, no editor lifecycle; 7.1 |
| `post.php` | `wp_cache_set_posts_last_changed` | Regular; existing cache invalidation mechanism |
| `rest-api.php` | `rest_get_route_for_post_type_items`, `rest_get_route_for_taxonomy_items` | Regular; registry reads and route-string construction, no object queries |

Original-source smoke probes verified Style Engine declarations/rules/store
roundtrip, schedule defaults, URL extraction, week boundaries, version predicates,
timezone offset, pattern normalization, credentials parsing and image quality.
These probes supplement source review; they do not replace addition tests.

Candidates that **must wait**:

- `wp_get_note_mentioned_user_ids`, `wp_strip_inline_note_markers`: useful string
  processing, but inherit the HTML named-entity problem in F01.
- `register_post_meta`, `register_term_meta`: inherit F04.
- `wp_check_password`: its legacy `$P$` path requires
  `ABSPATH . WPINC . '/class-phpass.php'`; an available class does not satisfy
  the unconditional file include on that branch.
- `wp_connectors_sanitize_application_password_credentials` and credential
  resolution: arbitrary connector option names are not in the runtime store.
- `wp_fast_hash`: requires sodium; availability in the audit container is not
  a declared library extension contract.
- `get_block_metadata_i18n_schema`: still loads a core file.
- `get_query_var`, `is_sitemap` and other query conditionals: require `$wp_query`.

Function-name availability alone must not promote candidates. Object methods,
includes and registered callbacks are part of the transitive chain too.

## Classes and adapters

No additional whole class is certified for unconditional inclusion. Prioritize
HTML initialization and the already-active partial-class contracts.

Existing discussion boundaries remain: block rendering/supports, pattern and
metadata loading, Theme JSON, uploads URL defaults, font resolution,
Interactivity API and sitemap routing need explicit runtime contracts. DB
models/queries, transports, filesystem loaders, admin/customizer lifecycles and
bundled library bootstraps remain unsuitable as unchanged whole copies.

Both `WP_Http` compatibility methods still serve copied consumers without the
transport class. `Source_Code_Replacer` also substitutes the Requests IPv6 check
and two HTTP status constants, so these raw-source missing-class references are
not runtime failures. Optional guarded `normalizer_*` and `exif_imagetype`
calls likewise must not be treated as unconditional missing dependencies.

The 31 selected adapter methods resolve their literal internal method calls.
`wpdb__Runtime` intentionally has no query API and uses adapted escaping;
`WP_REST_Server__Runtime` intentionally omits live serving and records headers
in memory. Those explicit adaptations differ from accidentally partial copies.

## Documentation and maintenance follow-up

- The previous audit's 819-function count, 7.0 target and exclusion totals were
  stale. Its blanket conclusion that all remaining exclusions are ineligible
  must not be reused.
- Class discussion/exclusion registries still quote a historical 7.0 inventory
  and 77 active classes; the current counts are 758 declarations and 78 active.
- `config/not-suitable-files.md` correctly lists current zero-active files;
  remove `cron.php` and `style-engine.php` only when their candidates are enabled.
- Rejection reasons for Style Engine, pattern normalization, schedule definitions,
  and version predicates are obsolete. Refresh them with actual implementation.
- The sitemap discussion links to missing
  `symbol-eligibility-discussion-rest-api.md`; REST adapter documentation now
  lives in `runtime.md` and README.
- Release docs promise copying `CHANGELOG.md`, but `RELEASE_FILES` omits it.
  `NOT_PUSH=1` still changes dependencies, writes the artifact worktree and
  resets the development checkout; it is not a read-only preview. The release
  flow was inspected only, not executed.

## Implementation order

1. Resolve incompatible active symbols, metadata callbacks, explicit option
   defaults, and partial-class contracts. Decide adaptation versus removal.
2. Refresh decision registries and run the supported WP-line matrix before
   publishing runtime changes.
