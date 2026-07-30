# Symbol Eligibility Last Audit

**Audit date:** 2026-07-30  
**Target:** bundled WordPress 7.0 and the current isolated runtime.

## Result

- Reviewed all **819** effective active functions: **88** were already
  `mockable`, and **731** regular functions received a fresh Auto-Mockable
  Review.
- Added `mockable` to **11** safe runtime boundaries:
  - registry readers: `get_all_registered_block_bindings_sources`,
    `get_block_bindings_source`, `wp_is_connector_registered`,
    `wp_get_connector`, `wp_get_connectors`, `is_registered_sidebar`;
  - locale providers: `wp_get_list_item_separator`,
    `wp_get_word_count_type`;
  - runtime-state providers: `wp_cache_get_multiple`, `ms_is_switched`;
  - randomness provider: `wp_rand`.
- Kept wrappers, deterministic helpers, state mutators, and functions with
  sufficient filter/action seams as regular copies.
- Each new `mockable` function has fallback and `WP_Mock` handler coverage.

## Eligibility Changes

The exclusion review made **30** previously disabled functions available.

Added directly:

`wp_rel_ugc`, `wp_pre_kses_block_attributes`, `is_login`,
`wp_is_json_request`, `wp_is_jsonp_request`, `wp_is_xml_request`,
`get_http_origin`, `get_allowed_http_origins`, `is_allowed_http_origin`,
`get_default_feed`, `html_type_rss`, `wp_is_local_html_output`,
`wp_is_connector_registered`, `wp_get_connector`, `wp_get_connectors`.

Added after small isolated-runtime support:

`register_sidebars`, `register_sidebar`, `unregister_sidebar`,
`is_registered_sidebar`, `register_block_pattern_category`,
`unregister_block_pattern_category`, `get_allowed_block_template_part_areas`,
`_filter_block_template_part_area`, `is_email_address_unsafe`,
`check_upload_mimes`, `upload_is_file_too_big`,
`users_can_register_signup_filter`, `get_space_allowed`,
`wp_should_replace_insecure_home_url`, `wp_replace_insecure_home_url`.

## Remaining Exclusions

- **1,637** function exclusion records:
  - 23 intentional custom mocks;
  - 8 redundant `cache-compat.php` implementations whose canonical versions
    are already copied from `cache.php`;
  - 1,606 still ineligible records because their dependency chains require DB,
    filesystem, network, full bootstrap, or request/admin lifecycle behavior.
- **9** classes remain excluded.
- No static or instance methods are currently excluded.

Exact symbol-level reasons remain next to their entries in `config/functions/`
and `config/classes.php`; `config/not-suitable-files.md` tracks reviewed files
without active functions.
