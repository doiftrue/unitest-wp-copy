# Symbol Eligibility Audit Inventory

Snapshot: **2026-09-19**, baseline `b3f484b`, bundled WordPress **7.1**.
See [findings and decisions](symbol-eligibility-last-audit.md) for interpretation.

## How to read this inventory

Every effective configured function (846) and whole class (78) appears below.
`R` is a regular copy; `M` is an auto-mockable copy. The marker column records
recommendations, not implemented changes. `Keep` means no marker change proposed;
it does **not** certify every branch as dependency-safe. F01–F09 refer to the
main audit. Shared dependencies propagate their limitations to callers.

`Fallback / handler` reports exact dedicated test-method names: `yes` means the
name was found in `tests/`; `review` means it was not. It is not line/branch
coverage: differently named or combined tests may exercise the function, and
existence of a test does not prove adequate assertions. `n/a` means no dedicated
handler test is required for the current regular-copy mode.

Named dependencies were checked against the bootstrapped runtime, with manual
classification of static rewrites and optional extension calls. This analysis
does not resolve arbitrary callback values, object-method targets, global data,
file includes or all version-specific behavior. Read the main findings before
using this inventory as an inclusion decision.

## Functions

### wp-admin/includes/screen.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `add_screen_option` | R | Keep | yes / n/a | — |
| `get_column_headers` | R | Keep | yes / n/a | — |
| `get_current_screen` | M | Keep | review / review | — |
| `set_current_screen` | R | Keep | yes / n/a | — |

### wp-admin/includes/template.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `convert_to_screen` | R | Keep | yes / n/a | — |

### wp-includes/abilities-api.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_get_abilities_match_meta` | R | Keep | yes / n/a | — |
| `wp_get_abilities` | R | Keep | yes / n/a | — |
| `wp_get_ability` | R | Add mockable | yes / n/a | — |
| `wp_get_ability_categories` | R | Add mockable | yes / n/a | — |
| `wp_get_ability_category` | R | Add mockable | yes / n/a | — |
| `wp_has_ability` | R | Add mockable | yes / n/a | — |
| `wp_has_ability_category` | R | Add mockable | yes / n/a | — |
| `wp_register_ability` | R | Keep | yes / n/a | — |
| `wp_register_ability_category` | R | Keep | yes / n/a | — |
| `wp_unregister_ability` | R | Keep | yes / n/a | — |
| `wp_unregister_ability_category` | R | Keep | yes / n/a | — |

### wp-includes/ai-client.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_supports_ai` | M | Keep | yes / yes | — |

### wp-includes/block-bindings.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `get_all_registered_block_bindings_sources` | M | Keep | yes / yes | — |
| `get_block_bindings_source` | M | Keep | yes / yes | — |
| `get_block_bindings_supported_attributes` | R | Keep | yes / n/a | — |
| `register_block_bindings_source` | R | Keep | yes / n/a | — |
| `unregister_block_bindings_source` | R | Keep | yes / n/a | — |

### wp-includes/block-editor.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `get_allowed_block_types` | R | Keep | yes / n/a | — |
| `get_default_block_categories` | R | Keep | yes / n/a | — |
| `wp_get_first_block` | R | Keep | yes / n/a | — |

### wp-includes/block-patterns.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `register_block_pattern_category` | R | Keep | yes / n/a | — |
| `unregister_block_pattern_category` | R | Keep | yes / n/a | — |

### wp-includes/block-template-utils.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_filter_block_template_part_area` | R | Keep | yes / n/a | — |
| `_flatten_blocks` | R | Keep | yes / n/a | — |
| `_inject_theme_attribute_in_template_part_block` | R | Keep | yes / n/a | — |
| `_remove_theme_attribute_from_template_part_block` | R | Keep | yes / n/a | — |
| `get_allowed_block_template_part_areas` | R | Keep | yes / n/a | — |
| `get_default_block_template_types` | R | Keep | yes / n/a | — |
| `get_template_hierarchy` | R | Keep | yes / n/a | — |

### wp-includes/block-template.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_block_template_add_skip_link` | R | Keep | yes / n/a | — |
| `_block_template_render_without_post_block_context` | R | Keep | yes / n/a | — |
| `_block_template_viewport_meta_tag` | R | Keep | yes / n/a | — |
| `_strip_template_file_suffix` | R | Keep | yes / n/a | — |
| `register_block_template` | R | Keep | yes / n/a | — |
| `unregister_block_template` | R | Keep | yes / n/a | — |

### wp-includes/blocks.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_filter_block_content_callback` | R | Keep | yes / n/a | — |
| `_restore_wpautop_hook` | R | Keep | yes / n/a | — |
| `_wp_filter_post_meta_footnotes` | R | Keep | yes / n/a | — |
| `_wp_footnotes_force_filtered_html_on_import_filter` | R | Keep | yes / n/a | — |
| `_wp_footnotes_kses_init_filters` | R | Keep | yes / n/a | — |
| `_wp_footnotes_remove_filters` | R | Keep | yes / n/a | — |
| `block_has_support` | R | Keep | yes / n/a | — |
| `excerpt_remove_footnotes` | R | Keep | yes / n/a | — |
| `extract_serialized_parent_block` | R | Keep | yes / n/a | — |
| `filter_block_content` | R | Keep | yes / n/a | — |
| `filter_block_core_template_part_attributes` | R | Keep | yes / n/a | — |
| `filter_block_kses` | R | Keep | yes / n/a | — |
| `filter_block_kses_value` | R | Keep | yes / n/a | — |
| `generate_block_asset_handle` | R | Keep | yes / n/a | — |
| `get_comment_delimited_block_content` | R | Keep | yes / n/a | — |
| `get_comments_pagination_arrow` | R | Keep | yes / n/a | — |
| `get_dynamic_block_names` | M | Keep | yes / yes | — |
| `get_hooked_blocks` | M | Keep | yes / yes | — |
| `get_query_pagination_arrow` | R | Keep | yes / n/a | — |
| `insert_hooked_blocks` | R | Keep | yes / n/a | — |
| `insert_hooked_blocks_and_set_ignored_hooked_blocks_metadata` | R | Keep | yes / n/a | — |
| `make_after_block_visitor` | R | Keep | yes / n/a | — |
| `parse_blocks` | R | Keep | yes / n/a | — |
| `register_block_style` | R | Keep | yes / n/a | — |
| `remove_block_asset_path_prefix` | R | Keep | yes / n/a | — |
| `remove_serialized_parent_block` | R | Keep | yes / n/a | — |
| `serialize_block` | R | Keep | yes / n/a | — |
| `serialize_block_attributes` | R | Keep | yes / n/a | — |
| `serialize_blocks` | R | Keep | yes / n/a | — |
| `set_ignored_hooked_blocks_metadata` | R | Keep | yes / n/a | — |
| `strip_core_block_namespace` | R | Keep | yes / n/a | — |
| `traverse_and_serialize_block` | R | Keep | yes / n/a | — |
| `traverse_and_serialize_blocks` | R | Keep | yes / n/a | — |
| `unregister_block_style` | R | Keep | yes / n/a | — |
| `unregister_block_type` | R | Keep | yes / n/a | — |
| `wp_migrate_old_typography_shape` | R | Keep | yes / n/a | — |

### wp-includes/bookmark.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `sanitize_bookmark` | R | Keep | yes / n/a | — |
| `sanitize_bookmark_field` | R | Keep | yes / n/a | — |

### wp-includes/cache-compat.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_cache_get_multiple_salted` | R | Keep | yes / n/a | — |
| `wp_cache_get_salted` | R | Keep | yes / n/a | — |
| `wp_cache_set_multiple_salted` | R | Keep | yes / n/a | — |
| `wp_cache_set_salted` | R | Keep | yes / n/a | — |

### wp-includes/cache.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_cache_add` | R | Keep | yes / n/a | — |
| `wp_cache_add_global_groups` | R | Keep | yes / n/a | — |
| `wp_cache_add_multiple` | R | Keep | yes / n/a | — |
| `wp_cache_add_non_persistent_groups` | R | Keep | yes / n/a | — |
| `wp_cache_close` | R | Keep | yes / n/a | — |
| `wp_cache_decr` | R | Keep | yes / n/a | — |
| `wp_cache_delete` | R | Keep | yes / n/a | — |
| `wp_cache_delete_multiple` | R | Keep | yes / n/a | — |
| `wp_cache_flush` | R | Keep | yes / n/a | — |
| `wp_cache_flush_group` | R | Keep | yes / n/a | — |
| `wp_cache_flush_runtime` | R | Keep | yes / n/a | — |
| `wp_cache_get` | R | Keep | yes / n/a | Keep regular pending reference-output handler support |
| `wp_cache_get_multiple` | M | Keep | yes / yes | — |
| `wp_cache_incr` | R | Keep | yes / n/a | — |
| `wp_cache_init` | R | Keep | yes / n/a | — |
| `wp_cache_replace` | R | Keep | yes / n/a | — |
| `wp_cache_set` | R | Keep | yes / n/a | — |
| `wp_cache_set_multiple` | R | Keep | yes / n/a | — |
| `wp_cache_supports` | R | Keep | yes / n/a | — |
| `wp_cache_switch_to_blog` | R | Keep | yes / n/a | — |

### wp-includes/canonical.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_remove_qs_args_if_not_in_url` | R | Keep | yes / n/a | — |
| `strip_fragment_from_url` | R | Keep | yes / n/a | — |

### wp-includes/capabilities.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_maybe_grant_install_languages_cap` | R | Keep | yes / n/a | — |
| `wp_maybe_grant_resume_extensions_caps` | R | Keep | yes / n/a | — |

### wp-includes/category-template.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_object_count_sort_cb` | R | Keep | yes / n/a | — |
| `_wp_object_name_sort_cb` | R | Keep | yes / n/a | — |
| `default_topic_count_scale` | R | Keep | yes / n/a | — |
| `wp_generate_tag_cloud` | R | Keep | yes / n/a | — |

### wp-includes/category.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_make_cat_compat` | R | Keep | yes / n/a | — |
| `sanitize_category` | R | Keep | yes / n/a | — |
| `sanitize_category_field` | R | Keep | yes / n/a | — |

### wp-includes/comment.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_clear_modified_cache_on_transition_comment_status` | R | Keep | yes / n/a | — |
| `clean_comment_cache` | R | Keep | yes / n/a | — |
| `get_comment_statuses` | R | Keep | yes / n/a | — |
| `separate_comments` | R | Keep | yes / n/a | — |
| `wp_cache_set_comments_last_changed` | R | Keep | yes / n/a | — |
| `wp_filter_comment` | R | Keep | yes / n/a | — |
| `wp_register_comment_personal_data_eraser` | R | Keep | yes / n/a | — |
| `wp_register_comment_personal_data_exporter` | R | Keep | yes / n/a | — |
| `wp_throttle_comment_flood` | R | Keep | yes / n/a | — |

### wp-includes/compat-utf8.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_has_noncharacters_fallback` | R | Keep | yes / n/a | — |
| `_wp_is_valid_utf8_fallback` | R | Keep | yes / n/a | — |
| `_wp_scan_utf8` | R | Keep | yes / n/a | — |
| `_wp_scrub_utf8_fallback` | R | Keep | yes / n/a | — |
| `_wp_utf8_codepoint_count` | R | Keep | yes / n/a | — |
| `_wp_utf8_codepoint_span` | R | Keep | yes / n/a | — |
| `_wp_utf8_decode_fallback` | R | Keep | yes / n/a | — |
| `_wp_utf8_encode_fallback` | R | Keep | yes / n/a | — |

### wp-includes/compat.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_` | R | Keep | yes / n/a | — |
| `_is_utf8_charset` | R | Keep | yes / n/a | — |
| `_mb_strlen` | R | Keep | review / n/a | — |
| `_mb_substr` | R | Keep | review / n/a | — |
| `_wp_can_use_pcre_u` | M | Keep | yes / yes | — |
| `array_all` | R | Keep | yes / n/a | — |
| `array_any` | R | Keep | yes / n/a | — |
| `array_find` | R | Keep | yes / n/a | — |
| `array_find_key` | R | Keep | yes / n/a | — |
| `array_first` | R | Keep | yes / n/a | — |
| `array_is_list` | R | Keep | yes / n/a | — |
| `array_last` | R | Keep | yes / n/a | — |
| `mb_strlen` | R | Keep | yes / n/a | — |
| `mb_substr` | R | Keep | yes / n/a | — |
| `str_contains` | R | Keep | yes / n/a | — |
| `str_ends_with` | R | Keep | yes / n/a | — |
| `str_starts_with` | R | Keep | yes / n/a | — |

### wp-includes/connectors.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_connectors_mask_api_key` | R | Keep | yes / n/a | — |
| `wp_get_connector` | M | Keep | yes / yes | — |
| `wp_get_connectors` | M | Keep | yes / yes | — |
| `wp_is_connector_registered` | M | Keep | yes / yes | — |

### wp-includes/default-constants.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_cookie_constants` | R | Keep | yes / n/a | — |
| `wp_functionality_constants` | R | Keep | yes / n/a | — |
| `wp_initial_constants` | R | Keep | yes / n/a | — |
| `wp_plugin_directory_constants` | R | Keep | yes / n/a | — |
| `wp_ssl_constants` | R | Keep | yes / n/a | — |

### wp-includes/deprecated.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `addslashes_gpc` | R | Keep | yes / n/a | — |
| `wp_sanitize_script_attributes` | R | Keep | yes / n/a | — |

### wp-includes/embed.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_oembed_create_xml` | R | Keep | yes / n/a | — |
| `_oembed_filter_feed_content` | R | Keep | yes / n/a | — |
| `wp_embed_defaults` | R | Keep | yes / n/a | — |
| `wp_embed_handler_audio` | R | Keep | yes / n/a | — |
| `wp_embed_handler_video` | R | Keep | yes / n/a | — |
| `wp_filter_oembed_iframe_title_attribute` | R | Keep | yes / n/a | — |
| `wp_maybe_enqueue_oembed_host_js` | R | Keep | yes / n/a | — |
| `wp_oembed_ensure_format` | R | Keep | yes / n/a | — |

### wp-includes/error-protection.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_get_extension_error_description` | R | Keep | yes / n/a | — |
| `wp_is_fatal_error_handler_enabled` | R | Keep | yes / n/a | — |

### wp-includes/feed.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `bloginfo_rss` | R | Keep | yes / n/a | — |
| `feed_content_type` | R | Keep | yes / n/a | — |
| `get_bloginfo_rss` | R | Keep | yes / n/a | — |
| `get_default_feed` | R | Keep | yes / n/a | — |
| `html_type_rss` | R | Keep | yes / n/a | — |
| `prep_atom_text_construct` | R | Keep | yes / n/a | — |

### wp-includes/formatting.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_autop_newline_preservation_helper` | R | Keep | review / n/a | — |
| `_deep_replace` | R | Keep | review / n/a | — |
| `_get_wptexturize_shortcode_regex` | R | Keep | review / n/a | — |
| `_get_wptexturize_split_regex` | R | Keep | review / n/a | — |
| `_links_add_base` | R | Keep | review / n/a | Static call rewritten; F09 has malformed test |
| `_links_add_target` | R | Keep | review / n/a | — |
| `_make_clickable_rel_attr` | R | Keep | yes / n/a | — |
| `_make_email_clickable_cb` | R | Keep | yes / n/a | — |
| `_make_url_clickable_cb` | R | Keep | yes / n/a | — |
| `_make_web_ftp_clickable_cb` | R | Keep | yes / n/a | — |
| `_sanitize_text_fields` | R | Keep | review / n/a | — |
| `_split_str_by_whitespace` | R | Keep | review / n/a | — |
| `_wp_emoji_list` | R | Keep | review / n/a | — |
| `_wp_iso_convert` | R | Keep | review / n/a | — |
| `_wp_specialchars` | R | Keep | review / n/a | — |
| `_wptexturize_pushpop_element` | R | Keep | review / n/a | — |
| `antispambot` | M | Keep | yes / yes | — |
| `backslashit` | R | Keep | yes / n/a | — |
| `balanceTags` | M | Keep | yes / yes | — |
| `capital_P_dangit` | R | Keep | yes / n/a | — |
| `convert_chars` | R | Keep | yes / n/a | — |
| `convert_invalid_entities` | R | Keep | yes / n/a | — |
| `convert_smilies` | M | Keep | yes / yes | — |
| `ent2ncr` | R | Keep | yes / n/a | — |
| `esc_attr` | R | Keep | yes / n/a | — |
| `esc_html` | R | Keep | yes / n/a | — |
| `esc_js` | R | Keep | yes / n/a | — |
| `esc_sql` | R | Keep | yes / n/a | — |
| `esc_textarea` | R | Keep | yes / n/a | — |
| `esc_url` | R | Keep | yes / n/a | — |
| `esc_url_raw` | R | Keep | yes / n/a | — |
| `esc_xml` | R | Keep | yes / n/a | — |
| `force_balance_tags` | R | Keep | yes / n/a | — |
| `format_for_editor` | R | Keep | yes / n/a | — |
| `format_to_edit` | R | Keep | yes / n/a | — |
| `get_date_from_gmt` | R | Keep | yes / n/a | — |
| `get_gmt_from_date` | R | Keep | yes / n/a | — |
| `get_html_split_regex` | R | Keep | yes / n/a | — |
| `get_url_in_content` | R | Keep | yes / n/a | — |
| `htmlentities2` | R | Keep | yes / n/a | — |
| `human_time_diff` | R | Keep | yes / n/a | — |
| `is_email` | R | Keep | yes / n/a | — |
| `iso8601_timezone_to_offset` | R | Keep | yes / n/a | — |
| `iso8601_to_datetime` | R | Keep | yes / n/a | — |
| `links_add_base_url` | R | Keep | yes / n/a | — |
| `links_add_target` | R | Keep | yes / n/a | — |
| `make_clickable` | R | Keep | yes / n/a | — |
| `map_deep` | R | Keep | yes / n/a | — |
| `maybe_hash_hex_color` | R | Keep | yes / n/a | — |
| `normalize_whitespace` | R | Keep | yes / n/a | — |
| `rawurlencode_deep` | R | Keep | yes / n/a | — |
| `remove_accents` | R | Keep | yes / n/a | Optional normalizer calls are guarded |
| `sanitize_email` | R | Keep | yes / n/a | — |
| `sanitize_file_name` | R | Keep | yes / n/a | — |
| `sanitize_hex_color` | R | Keep | yes / n/a | — |
| `sanitize_hex_color_no_hash` | R | Keep | yes / n/a | — |
| `sanitize_html_class` | R | Keep | yes / n/a | — |
| `sanitize_key` | R | Keep | yes / n/a | — |
| `sanitize_locale_name` | R | Keep | yes / n/a | — |
| `sanitize_mime_type` | R | Keep | yes / n/a | — |
| `sanitize_sql_orderby` | R | Keep | yes / n/a | — |
| `sanitize_text_field` | R | Keep | yes / n/a | — |
| `sanitize_textarea_field` | R | Keep | yes / n/a | — |
| `sanitize_title` | R | Keep | yes / n/a | — |
| `sanitize_title_for_query` | R | Keep | yes / n/a | — |
| `sanitize_title_with_dashes` | R | Keep | yes / n/a | — |
| `sanitize_trackback_urls` | R | Keep | yes / n/a | — |
| `sanitize_url` | R | Keep | yes / n/a | — |
| `sanitize_user` | R | Keep | yes / n/a | — |
| `seems_utf8` | R | Keep | yes / n/a | — |
| `shortcode_unautop` | R | Keep | yes / n/a | — |
| `stripslashes_deep` | R | Keep | yes / n/a | — |
| `stripslashes_from_strings_only` | R | Keep | yes / n/a | — |
| `tag_escape` | R | Keep | yes / n/a | — |
| `trailingslashit` | R | Keep | yes / n/a | — |
| `translate_smiley` | R | Keep | yes / n/a | — |
| `untrailingslashit` | R | Keep | yes / n/a | — |
| `url_shorten` | R | Keep | yes / n/a | — |
| `urldecode_deep` | R | Keep | yes / n/a | — |
| `urlencode_deep` | R | Keep | yes / n/a | — |
| `utf8_uri_encode` | R | Keep | yes / n/a | — |
| `wp_basename` | R | Keep | yes / n/a | — |
| `wp_check_invalid_utf8` | R | Keep | yes / n/a | — |
| `wp_encode_emoji` | R | Keep | yes / n/a | — |
| `wp_html_excerpt` | R | Keep | yes / n/a | — |
| `wp_html_split` | R | Keep | yes / n/a | — |
| `wp_iso_descrambler` | R | Keep | yes / n/a | — |
| `wp_make_link_relative` | R | Keep | yes / n/a | — |
| `wp_parse_str` | R | Keep | yes / n/a | — |
| `wp_pre_kses_block_attributes` | R | Keep | yes / n/a | — |
| `wp_pre_kses_less_than` | R | Keep | yes / n/a | — |
| `wp_pre_kses_less_than_callback` | R | Keep | yes / n/a | — |
| `wp_rel_callback` | R | Keep | yes / n/a | — |
| `wp_rel_nofollow` | R | Keep | yes / n/a | — |
| `wp_rel_ugc` | R | Keep | yes / n/a | — |
| `wp_replace_in_html_tags` | R | Keep | yes / n/a | — |
| `wp_slash` | R | Keep | yes / n/a | — |
| `wp_spaces_regexp` | R | Keep | yes / n/a | — |
| `wp_specialchars_decode` | R | Keep | yes / n/a | — |
| `wp_sprintf` | R | Keep | yes / n/a | — |
| `wp_sprintf_l` | R | Keep | yes / n/a | — |
| `wp_staticize_emoji` | R | Keep | yes / n/a | — |
| `wp_staticize_emoji_for_email` | R | Keep | yes / n/a | — |
| `wp_strip_all_tags` | R | Keep | yes / n/a | — |
| `wp_trim_excerpt` | R | Keep | yes / n/a | F03: unavailable default excerpt chain |
| `wp_trim_words` | R | Keep | yes / n/a | — |
| `wp_unslash` | R | Keep | yes / n/a | — |
| `wpautop` | R | Keep | yes / n/a | — |
| `wptexturize` | R | Keep | yes / n/a | — |
| `wptexturize_primes` | R | Keep | yes / n/a | — |
| `zeroise` | R | Keep | yes / n/a | — |

### wp-includes/functions.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `__return_empty_array` | R | Keep | yes / n/a | — |
| `__return_empty_string` | R | Keep | yes / n/a | — |
| `__return_false` | R | Keep | yes / n/a | — |
| `__return_null` | R | Keep | yes / n/a | — |
| `__return_true` | R | Keep | yes / n/a | — |
| `__return_zero` | R | Keep | yes / n/a | — |
| `_canonical_charset` | R | Keep | yes / n/a | — |
| `_cleanup_header_comment` | R | Keep | yes / n/a | — |
| `_deprecated_argument` | R | Keep | review / n/a | — |
| `_deprecated_constructor` | R | Keep | yes / n/a | — |
| `_deprecated_function` | M | Keep | review / review | — |
| `_deprecated_hook` | R | Keep | yes / n/a | — |
| `_doing_it_wrong` | R | Keep | review / n/a | — |
| `_get_non_cached_ids` | R | Keep | yes / n/a | — |
| `_http_build_query` | R | Keep | review / n/a | — |
| `_validate_cache_id` | R | Keep | yes / n/a | — |
| `_wp_array_get` | R | Keep | yes / n/a | — |
| `_wp_array_set` | R | Keep | yes / n/a | — |
| `_wp_json_convert_string` | R | Keep | review / n/a | — |
| `_wp_json_prepare_data` | R | Keep | yes / n/a | — |
| `_wp_json_sanity_check` | R | Keep | review / n/a | — |
| `_wp_mysql_week` | R | Keep | yes / n/a | — |
| `_wp_to_kebab_case` | R | Keep | yes / n/a | — |
| `add_query_arg` | R | Keep | review / n/a | — |
| `bool_from_yn` | R | Keep | yes / n/a | — |
| `build_query` | R | Keep | review / n/a | — |
| `current_datetime` | R | Add mockable | yes / n/a | — |
| `current_time` | M | Keep | yes / review | — |
| `date_i18n` | R | Keep | yes / n/a | — |
| `force_ssl_admin` | M | Keep | yes / review | — |
| `get_allowed_mime_types` | R | Keep | yes / n/a | F03: explicit-user capability path |
| `get_file_data` | R | Keep | yes / n/a | F07: file I/O |
| `get_status_header_desc` | R | Keep | yes / n/a | — |
| `get_tag_regex` | R | Keep | yes / n/a | — |
| `human_readable_duration` | R | Keep | yes / n/a | — |
| `is_serialized` | R | Keep | review / n/a | — |
| `is_serialized_string` | R | Keep | review / n/a | — |
| `is_utf8_charset` | M | Keep | yes / review | — |
| `maybe_serialize` | R | Keep | review / n/a | — |
| `maybe_unserialize` | R | Keep | review / n/a | — |
| `mbstring_binary_safe_encoding` | R | Keep | review / n/a | — |
| `mysql_to_rfc3339` | R | Keep | yes / n/a | — |
| `mysql2date` | R | Keep | yes / n/a | — |
| `number_format_i18n` | R | Keep | yes / n/a | — |
| `path_is_absolute` | R | Keep | review / n/a | F07: stream/filesystem probes |
| `path_join` | R | Keep | review / n/a | — |
| `remove_query_arg` | R | Keep | review / n/a | — |
| `reset_mbstring_encoding` | R | Keep | review / n/a | — |
| `size_format` | R | Keep | yes / n/a | — |
| `smilies_init` | R | Keep | yes / n/a | — |
| `validate_file` | R | Keep | yes / n/a | — |
| `wp_allowed_protocols` | R | Keep | yes / n/a | — |
| `wp_array_slice_assoc` | R | Keep | review / n/a | — |
| `wp_cache_get_last_changed` | R | Keep | yes / n/a | — |
| `wp_cache_set_last_changed` | R | Keep | yes / n/a | — |
| `wp_check_filetype` | R | Keep | yes / n/a | — |
| `wp_check_jsonp_callback` | R | Keep | yes / n/a | — |
| `wp_checkdate` | R | Keep | yes / n/a | — |
| `wp_date` | R | Keep | yes / n/a | F02: missing localized-date dependency |
| `wp_debug_backtrace_summary` | R | Keep | yes / n/a | — |
| `wp_ext2type` | R | Keep | yes / n/a | — |
| `wp_filter_object_list` | R | Keep | yes / n/a | — |
| `wp_find_hierarchy_loop` | R | Keep | yes / n/a | — |
| `wp_find_hierarchy_loop_tortoise_hare` | R | Keep | yes / n/a | — |
| `wp_fuzzy_number_match` | R | Keep | yes / n/a | — |
| `wp_generate_uuid4` | M | Keep | yes / yes | — |
| `wp_get_default_extension_for_mime_type` | R | Keep | yes / n/a | — |
| `wp_get_ext_types` | R | Keep | yes / n/a | — |
| `wp_get_image_mime` | R | Keep | yes / n/a | F07: file I/O; guarded EXIF dependency |
| `wp_get_mime_types` | R | Keep | yes / n/a | — |
| `wp_get_nocache_headers` | R | Keep | yes / n/a | — |
| `wp_is_heic_image_mime_type` | R | Keep | yes / n/a | — |
| `wp_is_numeric_array` | R | Keep | yes / n/a | — |
| `wp_is_stream` | R | Keep | yes / n/a | — |
| `wp_is_uuid` | R | Keep | yes / n/a | — |
| `wp_json_encode` | R | Keep | review / n/a | — |
| `wp_json_file_decode` | R | Keep | yes / n/a | F07: file I/O |
| `wp_list_filter` | R | Keep | yes / n/a | — |
| `wp_list_pluck` | R | Keep | yes / n/a | — |
| `wp_list_sort` | R | Keep | yes / n/a | — |
| `wp_normalize_path` | R | Keep | review / n/a | — |
| `wp_parse_args` | R | Keep | review / n/a | — |
| `wp_parse_id_list` | R | Keep | review / n/a | — |
| `wp_parse_list` | R | Keep | review / n/a | — |
| `wp_parse_slug_list` | R | Keep | review / n/a | — |
| `wp_privacy_anonymize_data` | R | Keep | yes / n/a | — |
| `wp_privacy_anonymize_ip` | R | Keep | yes / n/a | — |
| `wp_recursive_ksort` | R | Keep | yes / n/a | — |
| `wp_suspend_cache_addition` | M | Keep | review / review | — |
| `wp_suspend_cache_invalidation` | R | Keep | yes / n/a | — |
| `wp_timezone` | R | Keep | yes / n/a | — |
| `wp_timezone_string` | M | Keep | yes / review | — |
| `wp_trigger_error` | M | Keep | review / review | — |
| `wp_unique_id` | M | Keep | yes / yes | — |
| `wp_unique_id_from_values` | R | Keep | yes / n/a | — |
| `wp_unique_prefixed_id` | M | Keep | yes / yes | — |
| `wp_validate_boolean` | R | Keep | yes / n/a | — |

### wp-includes/functions.wp-scripts.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_scripts_add_args_data` | R | Keep | yes / n/a | — |
| `_wp_scripts_maybe_doing_it_wrong` | R | Keep | yes / n/a | — |
| `wp_add_inline_script` | R | Keep | yes / n/a | — |
| `wp_dequeue_script` | R | Keep | yes / n/a | — |
| `wp_deregister_script` | R | Keep | yes / n/a | — |
| `wp_enqueue_script` | R | Keep | yes / n/a | — |
| `wp_localize_script` | R | Keep | yes / n/a | — |
| `wp_print_scripts` | R | Keep | yes / n/a | — |
| `wp_register_script` | R | Keep | yes / n/a | — |
| `wp_script_add_data` | R | Keep | yes / n/a | — |
| `wp_script_is` | R | Keep | yes / n/a | — |
| `wp_scripts` | M | Keep | yes / yes | — |
| `wp_set_script_translations` | R | Keep | yes / n/a | — |

### wp-includes/functions.wp-styles.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_add_inline_style` | R | Keep | yes / n/a | — |
| `wp_dequeue_style` | R | Keep | yes / n/a | — |
| `wp_deregister_style` | R | Keep | yes / n/a | — |
| `wp_enqueue_style` | R | Keep | yes / n/a | — |
| `wp_print_styles` | R | Keep | yes / n/a | — |
| `wp_register_style` | R | Keep | yes / n/a | — |
| `wp_style_add_data` | R | Keep | yes / n/a | — |
| `wp_style_is` | R | Keep | yes / n/a | — |
| `wp_styles` | M | Keep | yes / yes | — |

### wp-includes/general-template.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `__checked_selected_helper` | R | Keep | review / n/a | — |
| `allowed_tags` | R | Keep | yes / n/a | — |
| `bloginfo` | R | Keep | yes / n/a | — |
| `calendar_week_mod` | R | Keep | yes / n/a | — |
| `checked` | R | Keep | yes / n/a | — |
| `disabled` | R | Keep | yes / n/a | — |
| `get_archives_link` | R | Keep | yes / n/a | — |
| `get_language_attributes` | R | Keep | yes / n/a | — |
| `language_attributes` | R | Keep | yes / n/a | — |
| `rsd_link` | R | Keep | yes / n/a | — |
| `selected` | R | Keep | yes / n/a | — |
| `wp_admin_css_color` | R | Keep | yes / n/a | — |
| `wp_body_open` | R | Keep | yes / n/a | — |
| `wp_dependencies_unique_hosts` | R | Keep | yes / n/a | — |
| `wp_footer` | R | Keep | yes / n/a | — |
| `wp_head` | R | Keep | yes / n/a | — |
| `wp_login_form` | R | Keep | yes / n/a | — |
| `wp_login_url` | R | Keep | yes / n/a | — |
| `wp_meta` | R | Keep | yes / n/a | — |
| `wp_preload_resources` | R | Keep | yes / n/a | — |
| `wp_readonly` | R | Keep | yes / n/a | — |
| `wp_registration_url` | R | Keep | yes / n/a | — |
| `wp_required_field_indicator` | R | Keep | yes / n/a | — |
| `wp_required_field_message` | R | Keep | yes / n/a | — |
| `wp_resource_hints` | R | Keep | yes / n/a | — |
| `wp_strict_cross_origin_referrer` | R | Keep | yes / n/a | — |

### wp-includes/global-styles-and-settings.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_get_block_name_from_theme_json_path` | R | Keep | yes / n/a | — |

### wp-includes/http.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_get_component_from_parsed_url_array` | R | Keep | yes / n/a | — |
| `_wp_translate_php_url_constant_to_key` | R | Keep | yes / n/a | — |
| `get_allowed_http_origins` | R | Keep | yes / n/a | — |
| `get_http_origin` | M | Keep | yes / yes | — |
| `is_allowed_http_origin` | R | Keep | yes / n/a | — |
| `wp_http_validate_url` | R | Keep | yes / n/a | F07: external DNS path |
| `wp_parse_url` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_body` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_cookie` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_cookie_value` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_cookies` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_header` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_headers` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_response_code` | R | Keep | yes / n/a | — |
| `wp_remote_retrieve_response_message` | R | Keep | yes / n/a | — |

### wp-includes/https-detection.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_is_home_url_using_https` | R | Keep | yes / n/a | — |
| `wp_is_local_html_output` | R | Keep | yes / n/a | — |
| `wp_is_site_url_using_https` | R | Keep | yes / n/a | — |
| `wp_is_using_https` | R | Keep | yes / n/a | — |

### wp-includes/https-migration.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_replace_insecure_home_url` | R | Keep | yes / n/a | — |
| `wp_should_replace_insecure_home_url` | R | Keep | yes / n/a | — |

### wp-includes/json-schema.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_get_json_schema_allowed_keywords` | R | Keep | yes / n/a | — |

### wp-includes/kses.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_add_global_attributes` | R | Keep | yes / n/a | — |
| `_wp_kses_decode_entities_chr` | R | Keep | yes / n/a | — |
| `_wp_kses_decode_entities_chr_hexdec` | R | Keep | yes / n/a | — |
| `_wp_kses_split_callback` | R | Keep | yes / n/a | — |
| `safecss_filter_attr` | R | Keep | yes / n/a | — |
| `valid_unicode` | R | Keep | yes / n/a | — |
| `wp_filter_global_styles_post` | R | Keep | yes / n/a | F03: missing Theme JSON |
| `wp_filter_kses` | R | Keep | yes / n/a | — |
| `wp_filter_nohtml_kses` | R | Keep | yes / n/a | — |
| `wp_filter_post_kses` | R | Keep | yes / n/a | — |
| `wp_kses` | R | Keep | yes / n/a | — |
| `wp_kses_allowed_html` | R | Keep | yes / n/a | — |
| `wp_kses_array_lc` | R | Keep | yes / n/a | — |
| `wp_kses_attr` | R | Keep | yes / n/a | — |
| `wp_kses_attr_check` | R | Keep | yes / n/a | — |
| `wp_kses_attr_parse` | R | Keep | yes / n/a | — |
| `wp_kses_bad_protocol` | R | Keep | yes / n/a | — |
| `wp_kses_bad_protocol_once` | R | Keep | yes / n/a | — |
| `wp_kses_bad_protocol_once2` | R | Keep | yes / n/a | — |
| `wp_kses_check_attr_val` | R | Keep | yes / n/a | — |
| `wp_kses_data` | R | Keep | yes / n/a | — |
| `wp_kses_decode_entities` | R | Keep | yes / n/a | — |
| `wp_kses_hair` | R | Keep | yes / n/a | — |
| `wp_kses_hair_parse` | R | Keep | yes / n/a | — |
| `wp_kses_hook` | R | Keep | yes / n/a | — |
| `wp_kses_html_error` | R | Keep | yes / n/a | — |
| `wp_kses_named_entities` | R | Keep | yes / n/a | — |
| `wp_kses_no_null` | R | Keep | yes / n/a | — |
| `wp_kses_normalize_entities` | R | Keep | yes / n/a | — |
| `wp_kses_normalize_entities2` | R | Keep | yes / n/a | — |
| `wp_kses_normalize_entities3` | R | Keep | yes / n/a | — |
| `wp_kses_one_attr` | R | Keep | yes / n/a | — |
| `wp_kses_post` | R | Keep | yes / n/a | — |
| `wp_kses_post_deep` | R | Keep | yes / n/a | — |
| `wp_kses_split` | R | Keep | yes / n/a | — |
| `wp_kses_split2` | R | Keep | yes / n/a | — |
| `wp_kses_stripslashes` | R | Keep | yes / n/a | — |
| `wp_kses_uri_attributes` | R | Keep | yes / n/a | — |
| `wp_kses_version` | R | Keep | yes / n/a | — |
| `wp_kses_xml_named_entities` | R | Keep | yes / n/a | — |

### wp-includes/l10n.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_n_noop` | R | Keep | yes / n/a | — |
| `_nx_noop` | R | Keep | yes / n/a | — |
| `before_last_bar` | R | Keep | yes / n/a | — |
| `get_locale` | M | Keep | yes / review | — |
| `is_rtl` | M | Keep | yes / yes | — |
| `translate_nooped_plural` | R | Keep | yes / n/a | — |
| `wp_get_list_item_separator` | M | Keep | yes / yes | — |
| `wp_get_word_count_type` | M | Keep | yes / yes | — |

### wp-includes/link-template.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_navigation_markup` | R | Keep | yes / n/a | — |
| `admin_url` | R | Keep | yes / n/a | — |
| `content_url` | M | Keep | yes / yes | — |
| `get_admin_url` | M | Keep | yes / review | — |
| `get_home_url` | M | Keep | yes / review | — |
| `get_parent_theme_file_path` | R | Keep | yes / n/a | — |
| `get_parent_theme_file_uri` | R | Keep | yes / n/a | — |
| `get_site_url` | M | Keep | yes / review | — |
| `get_theme_file_path` | R | Keep | yes / n/a | F07: filesystem probes |
| `get_theme_file_uri` | R | Keep | yes / n/a | F07: filesystem probes |
| `home_url` | R | Keep | yes / n/a | — |
| `includes_url` | M | Keep | yes / yes | — |
| `is_avatar_comment_type` | R | Keep | yes / n/a | — |
| `plugins_url` | M | Keep | yes / yes | — |
| `set_url_scheme` | R | Keep | yes / n/a | — |
| `site_url` | R | Keep | yes / n/a | — |
| `wp_internal_hosts` | R | Keep | yes / n/a | — |
| `wp_is_internal_link` | R | Keep | yes / n/a | — |

### wp-includes/load.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `absint` | R | Keep | yes / n/a | — |
| `get_current_blog_id` | M | Keep | yes / yes | — |
| `get_current_network_id` | M | Keep | yes / yes | F03: multisite fallback fails |
| `is_admin` | M | Keep | yes / yes | — |
| `is_blog_admin` | M | Keep | yes / yes | — |
| `is_login` | M | Keep | yes / yes | — |
| `is_multisite` | M | Keep | yes / yes | — |
| `is_network_admin` | M | Keep | yes / yes | — |
| `is_ssl` | M | Keep | yes / review | — |
| `is_user_admin` | M | Keep | yes / yes | — |
| `is_wp_error` | R | Keep | yes / n/a | — |
| `timer_float` | M | Keep | yes / yes | — |
| `timer_start` | R | Keep | yes / n/a | — |
| `timer_stop` | M | Keep | yes / yes | — |
| `wp_convert_hr_to_bytes` | R | Keep | yes / n/a | — |
| `wp_doing_ajax` | M | Keep | yes / review | — |
| `wp_doing_cron` | M | Keep | yes / yes | — |
| `wp_get_development_mode` | M | Keep | yes / review | — |
| `wp_get_environment_type` | M | Keep | yes / review | — |
| `wp_get_server_protocol` | M | Keep | yes / yes | — |
| `wp_installing` | M | Keep | yes / review | — |
| `wp_is_development_mode` | R | Keep | yes / n/a | — |
| `wp_is_file_mod_allowed` | M | Keep | yes / yes | — |
| `wp_is_ini_value_changeable` | R | Keep | yes / n/a | — |
| `wp_is_json_media_type` | R | Keep | yes / n/a | — |
| `wp_is_json_request` | M | Keep | yes / yes | — |
| `wp_is_jsonp_request` | M | Keep | yes / yes | — |
| `wp_is_xml_request` | M | Keep | yes / yes | — |
| `wp_using_themes` | M | Keep | yes / yes | — |

### wp-includes/media.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_add_additional_image_sizes` | R | Keep | yes / n/a | — |
| `_wp_get_attachment_relative_path` | R | Keep | yes / n/a | — |
| `_wp_get_image_size_from_meta` | R | Keep | yes / n/a | — |
| `_wp_post_thumbnail_class_filter` | R | Keep | yes / n/a | — |
| `_wp_post_thumbnail_class_filter_add` | R | Keep | yes / n/a | — |
| `_wp_post_thumbnail_class_filter_remove` | R | Keep | yes / n/a | — |
| `_wp_post_thumbnail_context_filter` | R | Keep | yes / n/a | — |
| `_wp_post_thumbnail_context_filter_add` | R | Keep | yes / n/a | — |
| `_wp_post_thumbnail_context_filter_remove` | R | Keep | yes / n/a | — |
| `add_image_size` | R | Keep | yes / n/a | — |
| `get_intermediate_image_sizes` | R | Keep | yes / n/a | — |
| `get_media_embedded_in_content` | R | Keep | yes / n/a | — |
| `has_image_size` | R | Keep | yes / n/a | — |
| `image_constrain_size_for_editor` | R | Keep | yes / n/a | — |
| `image_hwstring` | R | Keep | yes / n/a | — |
| `image_resize_dimensions` | R | Keep | yes / n/a | — |
| `remove_image_size` | R | Keep | yes / n/a | — |
| `set_post_thumbnail_size` | R | Keep | yes / n/a | — |
| `wp_constrain_dimensions` | R | Keep | yes / n/a | — |
| `wp_expand_dimensions` | R | Keep | yes / n/a | — |
| `wp_get_additional_image_sizes` | M | Keep | yes / yes | — |
| `wp_get_attachment_id3_keys` | R | Keep | yes / n/a | — |
| `wp_get_audio_extensions` | R | Keep | yes / n/a | — |
| `wp_get_image_editor_output_format` | R | Keep | yes / n/a | — |
| `wp_get_registered_image_subsizes` | R | Keep | yes / n/a | — |
| `wp_get_video_extensions` | R | Keep | yes / n/a | — |
| `wp_high_priority_element_flag` | R | Add mockable | yes / n/a | — |
| `wp_image_file_matches_image_meta` | R | Keep | yes / n/a | — |
| `wp_image_matches_ratio` | R | Keep | yes / n/a | — |
| `wp_image_src_get_dimensions` | R | Keep | yes / n/a | — |
| `wp_img_tag_add_auto_sizes` | R | Keep | yes / n/a | — |
| `wp_increase_content_media_count` | R | Keep | yes / n/a | — |
| `wp_lazy_loading_enabled` | R | Keep | yes / n/a | — |
| `wp_max_upload_size` | R | Keep | yes / n/a | — |
| `wp_maybe_add_fetchpriority_high_attr` | R | Keep | yes / n/a | — |
| `wp_mediaelement_fallback` | R | Keep | yes / n/a | — |
| `wp_omit_loading_attr_threshold` | R | Keep | yes / n/a | — |
| `wp_sizes_attribute_includes_valid_auto` | R | Keep | yes / n/a | — |

### wp-includes/meta.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_get_meta_table` | R | Keep | yes / n/a | — |
| `_wp_register_meta_args_allowed_list` | R | Keep | yes / n/a | — |
| `get_meta_sql` | R | Keep | yes / n/a | — |
| `get_metadata_default` | R | Keep | yes / n/a | F04: registered-default callback fails |
| `get_registered_meta_keys` | M | Keep | yes / yes | — |
| `is_protected_meta` | M | Redundant; retain for compatibility | yes / yes | — |
| `register_meta` | R | Keep | yes / n/a | F04: installs unavailable default callback |
| `registered_meta_key_exists` | R | Keep | yes / n/a | — |
| `sanitize_meta` | R | Keep | yes / n/a | — |
| `unregister_meta_key` | R | Keep | yes / n/a | — |

### wp-includes/ms-blogs.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `clean_site_details_cache` | R | Keep | yes / n/a | — |
| `ms_is_switched` | M | Keep | yes / yes | — |

### wp-includes/ms-functions.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `check_upload_mimes` | R | Keep | yes / n/a | — |
| `filter_SSL` | R | Keep | yes / n/a | — |
| `force_ssl_content` | R | Add mockable | yes / n/a | — |
| `get_current_site` | M | Keep | yes / yes | — |
| `get_space_allowed` | R | Keep | yes / n/a | — |
| `get_subdirectory_reserved_names` | R | Keep | yes / n/a | — |
| `is_email_address_unsafe` | R | Keep | yes / n/a | — |
| `upload_is_file_too_big` | R | Keep | yes / n/a | — |
| `users_can_register_signup_filter` | R | Keep | yes / n/a | — |

### wp-includes/ms-load.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `is_subdomain_install` | M | Keep | yes / yes | — |

### wp-includes/ms-network.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `clean_network_cache` | R | Keep | yes / n/a | — |
| `update_network_cache` | R | Keep | yes / n/a | — |

### wp-includes/ms-site.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_cache_set_sites_last_changed` | R | Keep | yes / n/a | — |

### wp-includes/nav-menu-template.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_nav_menu_item_id_use_once` | R | Keep | yes / n/a | — |
| `wp_nav_menu_remove_menu_item_has_children_class` | R | Keep | yes / n/a | — |

### wp-includes/nav-menu.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_is_valid_nav_menu_item` | R | Keep | yes / n/a | — |
| `_wp_reset_invalid_menu_item_parent` | R | Keep | yes / n/a | — |
| `get_registered_nav_menus` | M | Keep | yes / yes | — |
| `register_nav_menu` | R | Keep | yes / n/a | — |
| `register_nav_menus` | R | Keep | yes / n/a | — |
| `unregister_nav_menu` | R | Keep | yes / n/a | — |
| `wp_map_nav_menu_locations` | R | Keep | yes / n/a | — |

### wp-includes/option.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `filter_default_option` | R | Keep | yes / n/a | — |
| `get_registered_settings` | M | Keep | yes / yes | — |
| `register_setting` | R | Keep | yes / n/a | — |
| `unregister_setting` | R | Keep | yes / n/a | — |
| `wp_autoload_values_to_autoload` | R | Keep | yes / n/a | — |
| `wp_determine_option_autoload_value` | R | Keep | yes / n/a | — |
| `wp_filter_default_autoload_value_via_option_size` | R | Keep | yes / n/a | — |

### wp-includes/pluggable.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_sanitize_utf8_in_redirect` | R | Keep | yes / n/a | — |
| `wp_generate_password` | R | Keep | yes / n/a | — |
| `wp_hash` | R | Keep | yes / n/a | — |
| `wp_hash_password` | R | Keep | yes / n/a | — |
| `wp_nonce_tick` | M | Keep | yes / review | — |
| `wp_parse_auth_cookie` | R | Add mockable | yes / n/a | — |
| `wp_password_needs_rehash` | R | Keep | yes / n/a | — |
| `wp_rand` | M | Keep | yes / yes | Unsupported transient fallback after entropy failure |
| `wp_sanitize_redirect` | R | Keep | yes / n/a | — |
| `wp_validate_redirect` | R | Keep | yes / n/a | — |

### wp-includes/plugin.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_call_all_hook` | R | Keep | yes / n/a | — |
| `_wp_filter_build_unique_id` | R | Keep | yes / n/a | — |
| `add_action` | R | Keep | yes / n/a | — |
| `add_filter` | R | Keep | yes / n/a | — |
| `apply_filters` | R | Keep | yes / n/a | — |
| `apply_filters_deprecated` | R | Keep | yes / n/a | — |
| `apply_filters_ref_array` | R | Keep | yes / n/a | — |
| `current_action` | R | Keep | yes / n/a | — |
| `current_filter` | R | Keep | yes / n/a | — |
| `did_action` | R | Keep | yes / n/a | — |
| `did_filter` | R | Keep | yes / n/a | — |
| `do_action` | R | Keep | yes / n/a | — |
| `do_action_deprecated` | R | Keep | yes / n/a | — |
| `do_action_ref_array` | R | Keep | yes / n/a | — |
| `doing_action` | R | Keep | yes / n/a | — |
| `doing_filter` | R | Keep | yes / n/a | — |
| `has_action` | R | Keep | yes / n/a | — |
| `has_filter` | R | Keep | yes / n/a | — |
| `plugin_basename` | R | Keep | yes / n/a | — |
| `plugin_dir_path` | R | Keep | yes / n/a | — |
| `plugin_dir_url` | R | Keep | yes / n/a | — |
| `register_activation_hook` | R | Keep | yes / n/a | — |
| `register_deactivation_hook` | R | Keep | yes / n/a | — |
| `register_uninstall_hook` | R | Keep | yes / n/a | F03: option persistence |
| `remove_action` | R | Keep | yes / n/a | — |
| `remove_all_actions` | R | Keep | yes / n/a | — |
| `remove_all_filters` | R | Keep | yes / n/a | — |
| `remove_filter` | R | Keep | yes / n/a | — |
| `wp_register_plugin_realpath` | R | Keep | yes / n/a | — |

### wp-includes/post-formats.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_post_format_get_term` | R | Keep | yes / n/a | — |
| `_post_format_get_terms` | R | Keep | yes / n/a | — |
| `_post_format_wp_get_object_terms` | R | Keep | yes / n/a | — |
| `get_post_format_slugs` | R | Keep | yes / n/a | — |
| `get_post_format_string` | R | Keep | yes / n/a | — |
| `get_post_format_strings` | R | Keep | yes / n/a | — |

### wp-includes/post.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_get_custom_object_labels` | R | Keep | yes / n/a | — |
| `_page_traverse_name` | R | Keep | yes / n/a | — |
| `_post_type_meta_capabilities` | R | Keep | yes / n/a | — |
| `_truncate_post_slug` | R | Keep | yes / n/a | — |
| `_wp_privacy_statuses` | R | Keep | yes / n/a | — |
| `add_post_type_support` | R | Keep | yes / n/a | — |
| `get_all_post_type_supports` | M | Keep | yes / yes | — |
| `get_extended` | R | Keep | yes / n/a | — |
| `get_page_children` | R | Keep | yes / n/a | — |
| `get_page_hierarchy` | R | Keep | yes / n/a | — |
| `get_page_statuses` | R | Keep | yes / n/a | — |
| `get_post_mime_types` | R | Keep | yes / n/a | — |
| `get_post_stati` | M | Keep | yes / yes | — |
| `get_post_status_object` | M | Keep | yes / yes | — |
| `get_post_statuses` | R | Keep | yes / n/a | — |
| `get_post_type_capabilities` | R | Keep | yes / n/a | — |
| `get_post_type_object` | M | Keep | yes / review | — |
| `get_post_types` | M | Keep | yes / review | — |
| `get_post_types_by_support` | M | Keep | yes / yes | — |
| `is_post_status_viewable` | M | Redundant; retain for compatibility | yes / review | — |
| `is_post_type_hierarchical` | M | Redundant; retain for compatibility | yes / review | — |
| `is_post_type_viewable` | M | Redundant; retain for compatibility | yes / review | — |
| `post_type_exists` | M | Redundant; retain for compatibility | yes / review | — |
| `post_type_supports` | M | Keep | yes / yes | — |
| `register_post_status` | R | Keep | yes / n/a | — |
| `remove_post_type_support` | R | Keep | yes / n/a | — |
| `use_block_editor_for_post_type` | R | Keep | yes / n/a | — |
| `wp_match_mime_types` | R | Keep | yes / n/a | — |
| `wp_post_mime_type_where` | R | Keep | yes / n/a | — |
| `wp_resolve_post_date` | R | Keep | yes / n/a | — |
| `wp_untrash_post_set_previous_status` | R | Keep | yes / n/a | — |

### wp-includes/rest-api.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_rest_array_intersect_key_recursive` | R | Keep | yes / n/a | — |
| `register_rest_field` | R | Keep | yes / n/a | — |
| `register_rest_route` | R | Keep | yes / n/a | — |
| `rest_are_values_equal` | R | Keep | yes / n/a | — |
| `rest_authorization_required_code` | R | Keep | yes / n/a | — |
| `rest_convert_error_to_response` | R | Keep | yes / n/a | — |
| `rest_default_additional_properties_to_false` | R | Keep | yes / n/a | — |
| `rest_do_request` | R | Keep | yes / n/a | — |
| `rest_ensure_request` | R | Keep | yes / n/a | — |
| `rest_ensure_response` | R | Keep | yes / n/a | — |
| `rest_filter_response_by_context` | R | Keep | yes / n/a | — |
| `rest_filter_response_fields` | R | Keep | yes / n/a | — |
| `rest_find_any_matching_schema` | R | Keep | yes / n/a | — |
| `rest_find_matching_pattern_property_schema` | R | Keep | yes / n/a | — |
| `rest_find_one_matching_schema` | R | Keep | yes / n/a | — |
| `rest_format_combining_operation_error` | R | Keep | yes / n/a | — |
| `rest_get_allowed_schema_keywords` | R | Keep | yes / n/a | — |
| `rest_get_avatar_sizes` | R | Keep | yes / n/a | — |
| `rest_get_best_type_for_value` | R | Keep | yes / n/a | — |
| `rest_get_combining_operation_error` | R | Keep | yes / n/a | — |
| `rest_get_date_with_gmt` | R | Keep | yes / n/a | — |
| `rest_get_endpoint_args_for_schema` | R | Keep | yes / n/a | — |
| `rest_get_server` | R | Keep | yes / n/a | — |
| `rest_get_url_prefix` | R | Keep | yes / n/a | — |
| `rest_handle_deprecated_argument` | M | Keep | yes / review | F07: header side effects |
| `rest_handle_deprecated_function` | M | Keep | yes / review | F07: header side effects |
| `rest_handle_doing_it_wrong` | M | Keep | yes / review | F07: header side effects |
| `rest_handle_multi_type_schema` | R | Keep | yes / n/a | — |
| `rest_handle_options_request` | R | Keep | yes / n/a | — |
| `rest_is_array` | R | Keep | yes / n/a | — |
| `rest_is_boolean` | R | Keep | yes / n/a | — |
| `rest_is_field_included` | R | Keep | yes / n/a | — |
| `rest_is_integer` | R | Keep | yes / n/a | — |
| `rest_is_ip_address` | R | Keep | yes / n/a | Requests IPv6 dependency rewritten |
| `rest_is_object` | R | Keep | yes / n/a | — |
| `rest_parse_date` | R | Keep | yes / n/a | — |
| `rest_parse_embed_param` | R | Keep | yes / n/a | — |
| `rest_parse_hex_color` | R | Keep | yes / n/a | — |
| `rest_parse_request_arg` | R | Keep | yes / n/a | — |
| `rest_sanitize_array` | R | Keep | yes / n/a | — |
| `rest_sanitize_boolean` | R | Keep | yes / n/a | — |
| `rest_sanitize_object` | R | Keep | yes / n/a | — |
| `rest_sanitize_request_arg` | R | Keep | yes / n/a | — |
| `rest_sanitize_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_send_allow_header` | R | Keep | yes / n/a | — |
| `rest_stabilize_value` | R | Keep | yes / n/a | — |
| `rest_url` | R | Keep | yes / n/a | — |
| `rest_validate_array_contains_unique_items` | R | Keep | yes / n/a | — |
| `rest_validate_array_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_validate_boolean_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_validate_enum` | R | Keep | yes / n/a | — |
| `rest_validate_integer_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_validate_json_schema_pattern` | R | Keep | yes / n/a | — |
| `rest_validate_null_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_validate_number_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_validate_object_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_validate_request_arg` | R | Keep | yes / n/a | — |
| `rest_validate_string_value_from_schema` | R | Keep | yes / n/a | — |
| `rest_validate_value_from_schema` | R | Keep | yes / n/a | — |

### wp-includes/revision.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_get_post_revision_version` | R | Keep | yes / n/a | — |

### wp-includes/rewrite.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_filter_taxonomy_base` | R | Keep | yes / n/a | — |

### wp-includes/robots-template.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_robots` | R | Keep | yes / n/a | — |
| `wp_robots_max_image_preview_large` | R | Keep | yes / n/a | F06: missing blog_public |
| `wp_robots_no_robots` | R | Keep | yes / n/a | F06: missing blog_public |
| `wp_robots_noindex` | R | Keep | yes / n/a | F06: missing blog_public |
| `wp_robots_sensitive_page` | R | Keep | yes / n/a | — |

### wp-includes/script-loader.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_print_scripts` | R | Keep | yes / n/a | — |
| `_wp_normalize_relative_css_links` | R | Keep | yes / n/a | — |
| `wp_filter_out_block_nodes` | R | Keep | yes / n/a | — |
| `wp_get_inline_script_tag` | R | Keep | yes / n/a | — |
| `wp_get_script_tag` | R | Keep | yes / n/a | — |
| `wp_html_custom_data_attribute_name` | R | Keep | yes / n/a | — |
| `wp_js_dataset_name` | R | Keep | yes / n/a | — |
| `wp_print_inline_script_tag` | R | Keep | yes / n/a | — |
| `wp_print_script_tag` | R | Keep | yes / n/a | — |
| `wp_prototype_before_jquery` | R | Keep | yes / n/a | — |
| `wp_remove_surrounding_empty_script_tags` | R | Keep | yes / n/a | — |

### wp-includes/script-modules.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_dequeue_script_module` | R | Keep | yes / n/a | — |
| `wp_deregister_script_module` | R | Keep | yes / n/a | — |
| `wp_enqueue_script_module` | R | Keep | yes / n/a | — |
| `wp_register_script_module` | R | Keep | yes / n/a | — |
| `wp_script_modules` | M | Keep | yes / yes | — |

### wp-includes/shortcodes.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_filter_do_shortcode_context` | R | Keep | yes / n/a | — |
| `add_shortcode` | R | Keep | yes / n/a | — |
| `apply_shortcodes` | R | Keep | yes / n/a | — |
| `do_shortcode` | R | Keep | yes / n/a | — |
| `do_shortcode_tag` | R | Keep | yes / n/a | — |
| `do_shortcodes_in_html_tags` | R | Keep | yes / n/a | — |
| `get_shortcode_atts_regex` | R | Keep | yes / n/a | — |
| `get_shortcode_regex` | R | Keep | yes / n/a | — |
| `get_shortcode_tags_in_content` | R | Keep | yes / n/a | — |
| `has_shortcode` | R | Keep | yes / n/a | — |
| `remove_all_shortcodes` | R | Keep | yes / n/a | — |
| `remove_shortcode` | R | Keep | yes / n/a | — |
| `shortcode_atts` | R | Keep | yes / n/a | — |
| `shortcode_exists` | R | Add mockable | yes / n/a | — |
| `shortcode_parse_atts` | R | Keep | yes / n/a | — |
| `strip_shortcode_tag` | R | Keep | yes / n/a | — |
| `strip_shortcodes` | R | Keep | yes / n/a | — |
| `unescape_invalid_shortcodes` | R | Keep | yes / n/a | — |

### wp-includes/sitemaps.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_sitemaps_get_max_urls` | R | Keep | yes / n/a | — |

### wp-includes/taxonomy.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `get_taxonomies` | M | Keep | yes / yes | — |
| `get_taxonomy` | M | Keep | yes / yes | — |
| `is_taxonomy_hierarchical` | M | Redundant; retain for compatibility | yes / yes | — |
| `is_taxonomy_viewable` | M | Redundant; retain for compatibility | yes / yes | — |
| `register_taxonomy_for_object_type` | R | Keep | yes / n/a | — |
| `sanitize_term` | R | Keep | yes / n/a | — |
| `sanitize_term_field` | R | Keep | yes / n/a | — |
| `taxonomy_exists` | M | Keep | yes / yes | — |
| `unregister_taxonomy_for_object_type` | R | Keep | yes / n/a | — |

### wp-includes/theme.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_remove_theme_support` | R | Keep | yes / n/a | — |
| `add_theme_support` | R | Keep | yes / n/a | — |
| `create_initial_theme_features` | R | Keep | yes / n/a | — |
| `current_theme_supports` | M | Keep | yes / yes | — |
| `get_locale_stylesheet_uri` | R | Keep | yes / n/a | F07: filesystem probes |
| `get_registered_theme_feature` | M | Keep | yes / yes | — |
| `get_registered_theme_features` | M | Keep | yes / yes | — |
| `get_stylesheet` | M | Keep | yes / yes | — |
| `get_stylesheet_uri` | R | Keep | yes / n/a | — |
| `get_template` | M | Keep | yes / yes | — |
| `get_theme_support` | M | Keep | yes / yes | — |
| `register_theme_feature` | R | Keep | yes / n/a | — |
| `remove_theme_support` | R | Keep | yes / n/a | — |

### wp-includes/user.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_wp_privacy_action_request_types` | R | Keep | yes / n/a | — |
| `sanitize_user_field` | R | Keep | yes / n/a | — |
| `validate_username` | R | Keep | yes / n/a | — |
| `wp_cache_set_users_last_changed` | R | Keep | yes / n/a | — |
| `wp_get_password_hint` | R | Keep | yes / n/a | — |
| `wp_get_session_token` | M | Keep | yes / yes | — |
| `wp_is_application_passwords_available` | R | Keep | yes / n/a | — |
| `wp_is_application_passwords_supported` | M | Keep | yes / yes | — |
| `wp_register_user_personal_data_exporter` | R | Keep | yes / n/a | — |
| `wp_user_request_action_description` | R | Keep | yes / n/a | — |

### wp-includes/utf8.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_has_noncharacters` | R | Keep | yes / n/a | — |
| `wp_is_valid_utf8` | R | Keep | yes / n/a | — |
| `wp_scrub_utf8` | R | Keep | yes / n/a | — |

### wp-includes/vars.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `wp_is_mobile` | M | Keep | yes / yes | — |

### wp-includes/widgets.php

| Function | Mode | Marker recommendation | Fallback / handler | Finding |
| --- | --- | --- | --- | --- |
| `_get_widget_id_base` | R | Keep | yes / n/a | — |
| `is_registered_sidebar` | M | Keep | yes / yes | — |
| `register_sidebar` | R | Keep | yes / n/a | — |
| `register_sidebars` | R | Keep | yes / n/a | — |
| `unregister_sidebar` | R | Keep | yes / n/a | — |
| `wp_parse_widget_id` | R | Keep | yes / n/a | — |

## Whole copied classes

The last column lists unresolved literal named calls in the generated class body.
An em dash means none were found, not that every inherited/dynamic path was
proven safe. Source paths identify the corresponding class config entries.

| Class | Core source | Review note | Unresolved named calls |
| --- | --- | --- | --- |
| `_WP_Dependency` | `wp-includes/class-wp-dependency.php` | No additional blocker identified by named-call scan | — |
| `Gettext_Translations` | `wp-includes/pomo/translations.php` | No additional blocker identified by named-call scan | — |
| `NOOP_Translations` | `wp-includes/pomo/translations.php` | No additional blocker identified by named-call scan | — |
| `PasswordHash` | `wp-includes/class-phpass.php` | No additional blocker identified by named-call scan | — |
| `Plural_Forms` | `wp-includes/pomo/plural-forms.php` | No additional blocker identified by named-call scan | — |
| `POMO_Reader` | `wp-includes/pomo/streams.php` | No additional blocker identified by named-call scan | — |
| `POMO_StringReader` | `wp-includes/pomo/streams.php` | No additional blocker identified by named-call scan | — |
| `Translation_Entry` | `wp-includes/pomo/entry.php` | No additional blocker identified by named-call scan | — |
| `Translations` | `wp-includes/pomo/translations.php` | No additional blocker identified by named-call scan | — |
| `Walker` | `wp-includes/class-wp-walker.php` | No additional blocker identified by named-call scan | — |
| `WP_Abilities_Registry` | `wp-includes/abilities-api/class-wp-abilities-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Ability` | `wp-includes/abilities-api/class-wp-ability.php` | No additional blocker identified by named-call scan | — |
| `WP_Ability_Categories_Registry` | `wp-includes/abilities-api/class-wp-ability-categories-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Ability_Category` | `wp-includes/abilities-api/class-wp-ability-category.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Bindings_Registry` | `wp-includes/class-wp-block-bindings-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Bindings_Source` | `wp-includes/class-wp-block-bindings-source.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Editor_Context` | `wp-includes/class-wp-block-editor-context.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Parser` | `wp-includes/class-wp-block-parser.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Parser_Block` | `wp-includes/class-wp-block-parser-block.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Parser_Frame` | `wp-includes/class-wp-block-parser-frame.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Pattern_Categories_Registry` | `wp-includes/class-wp-block-pattern-categories-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Processor` | `wp-includes/class-wp-block-processor.php` | Streaming parser uses WP_HTML_Span; no named-entity decoder dependency | — |
| `WP_Block_Styles_Registry` | `wp-includes/class-wp-block-styles-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Template` | `wp-includes/class-wp-block-template.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Templates_Registry` | `wp-includes/class-wp-block-templates-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Type` | `wp-includes/class-wp-block-type.php` | No additional blocker identified by named-call scan | — |
| `WP_Block_Type_Registry` | `wp-includes/class-wp-block-type-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Connector_Registry` | `wp-includes/class-wp-connector-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Date_Query` | `wp-includes/class-wp-date-query.php` | SQL/date construction uses available timezone helpers and the non-querying wpdb adapter | — |
| `WP_Dependencies` | `wp-includes/class-wp-dependencies.php` | No additional blocker identified by named-call scan | — |
| `WP_Error` | `wp-includes/class-wp-error.php` | No additional blocker identified by named-call scan | — |
| `WP_Exception` | `wp-includes/class-wp-exception.php` | No additional blocker identified by named-call scan | — |
| `WP_Filter_Sentinel` | `wp-includes/class-wp-filter-sentinel.php` | No additional blocker identified by named-call scan | — |
| `WP_Font_Face` | `wp-includes/fonts/class-wp-font-face.php` | No additional blocker identified by named-call scan | — |
| `WP_Font_Utils` | `wp-includes/fonts/class-wp-font-utils.php` | No additional blocker identified by named-call scan | — |
| `WP_Hook` | `wp-includes/class-wp-hook.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Active_Formatting_Elements` | `wp-includes/html-api/class-wp-html-active-formatting-elements.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Attribute_Token` | `wp-includes/html-api/class-wp-html-attribute-token.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Decoder` | `wp-includes/html-api/class-wp-html-decoder.php` | F01: missing named-character-reference data | — |
| `WP_HTML_Doctype_Info` | `wp-includes/html-api/class-wp-html-doctype-info.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Open_Elements` | `wp-includes/html-api/class-wp-html-open-elements.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Processor` | `wp-includes/html-api/class-wp-html-processor.php` | F01: inherits HTML decoding limitation | — |
| `WP_HTML_Processor_State` | `wp-includes/html-api/class-wp-html-processor-state.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Span` | `wp-includes/html-api/class-wp-html-span.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Stack_Event` | `wp-includes/html-api/class-wp-html-stack-event.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Tag_Processor` | `wp-includes/html-api/class-wp-html-tag-processor.php` | F01: named-entity decoding fails | — |
| `WP_HTML_Text_Replacement` | `wp-includes/html-api/class-wp-html-text-replacement.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Token` | `wp-includes/html-api/class-wp-html-token.php` | No additional blocker identified by named-call scan | — |
| `WP_HTML_Unsupported_Exception` | `wp-includes/html-api/class-wp-html-unsupported-exception.php` | No additional blocker identified by named-call scan | — |
| `WP_Http_Cookie` | `wp-includes/class-wp-http-cookie.php` | No additional blocker identified by named-call scan | — |
| `WP_Http_Encoding` | `wp-includes/class-wp-http-encoding.php` | No additional blocker identified by named-call scan | — |
| `WP_HTTP_Proxy` | `wp-includes/class-wp-http-proxy.php` | No additional blocker identified by named-call scan | — |
| `WP_HTTP_Response` | `wp-includes/class-wp-http-response.php` | No additional blocker identified by named-call scan | — |
| `WP_Interactivity_API_Directives_Processor` | `wp-includes/interactivity-api/class-wp-interactivity-api-directives-processor.php` | F01: inherits HTML processor limitation | — |
| `WP_List_Util` | `wp-includes/class-wp-list-util.php` | No additional blocker identified by named-call scan | — |
| `WP_Locale` | `wp-includes/class-wp-locale.php` | No additional blocker identified by named-call scan | — |
| `WP_MatchesMapRegex` | `wp-includes/class-wp-matchesmapregex.php` | No additional blocker identified by named-call scan | — |
| `WP_Meta_Query` | `wp-includes/class-wp-meta-query.php` | Uses explicitly adapted non-querying wpdb; no database execution | — |
| `WP_Object_Cache` | `wp-includes/class-wp-object-cache.php` | No additional blocker identified by named-call scan | — |
| `WP_REST_Controller` | `wp-includes/rest-api/endpoints/class-wp-rest-controller.php` | No additional blocker identified by named-call scan | — |
| `WP_REST_Request` | `wp-includes/rest-api/class-wp-rest-request.php` | No additional blocker identified by named-call scan | — |
| `WP_REST_Response` | `wp-includes/rest-api/class-wp-rest-response.php` | No additional blocker identified by named-call scan | — |
| `WP_Screen` | `wp-admin/includes/class-wp-screen.php` | F05: partial admin/user contract | `wp_die`, `get_post`, `use_block_editor_for_post`, `is_object_in_taxonomy`, `get_user_option`, `get_user_setting`, `wp_nonce_field`, `submit_button`, `meta_box_prefs`, `update_user_meta`, `get_current_user_id`, `get_user_meta`, `wp_get_current_user`, `get_hidden_columns` |
| `WP_Script_Modules` | `wp-includes/class-wp-script-modules.php` | F05: theme hooks / translation loader | `load_script_module_textdomain`, `wp_is_block_theme` |
| `WP_Scripts` | `wp-includes/class-wp-scripts.php` | F05: unavailable translation loader | `load_script_textdomain` |
| `WP_Sitemaps_Index` | `wp-includes/sitemaps/class-wp-sitemaps-index.php` | No additional blocker identified by named-call scan | — |
| `WP_Sitemaps_Provider` | `wp-includes/sitemaps/class-wp-sitemaps-provider.php` | No additional blocker identified by named-call scan | — |
| `WP_Sitemaps_Registry` | `wp-includes/sitemaps/class-wp-sitemaps-registry.php` | No additional blocker identified by named-call scan | — |
| `WP_Sitemaps_Renderer` | `wp-includes/sitemaps/class-wp-sitemaps-renderer.php` | F05: XML helper plus live serving/error paths | `wp_die` |
| `WP_Speculation_Rules` | `wp-includes/class-wp-speculation-rules.php` | No additional blocker identified by named-call scan | — |
| `WP_Style_Engine` | `wp-includes/style-engine/class-wp-style-engine.php` | Required classes are available; its three public function wrappers remain excluded | — |
| `WP_Style_Engine_CSS_Declarations` | `wp-includes/style-engine/class-wp-style-engine-css-declarations.php` | No additional blocker identified by named-call scan | — |
| `WP_Style_Engine_CSS_Rule` | `wp-includes/style-engine/class-wp-style-engine-css-rule.php` | No additional blocker identified by named-call scan | — |
| `WP_Style_Engine_CSS_Rules_Store` | `wp-includes/style-engine/class-wp-style-engine-css-rules-store.php` | No additional blocker identified by named-call scan | — |
| `WP_Style_Engine_Processor` | `wp-includes/style-engine/class-wp-style-engine-processor.php` | No additional blocker identified by named-call scan | — |
| `WP_Styles` | `wp-includes/class-wp-styles.php` | No additional blocker identified by named-call scan | — |
| `WP_Token_Map` | `wp-includes/class-wp-token-map.php` | No additional blocker identified by named-call scan | — |
| `WP_Widget_Factory` | `wp-includes/class-wp-widget-factory.php` | No additional blocker identified by named-call scan | — |

## Methods and adapters

- Static compatibility functions: `WP_Http__make_absolute_url`,
  `WP_Http__is_ip_address`; source selection is in `config/static-methods.php`.
- `wpdb__Runtime`: seven copied methods selected in `config/instance-methods.php`.
- `WP_REST_Server__Runtime`: 24 copied methods selected in that same config.
- Literal internal method calls resolve in both adapters. See `SYMBOLS-INFO.md`
  for their public copied/adapted method surfaces, and the main audit for limits.

## Coverage totals

- 846 effective configured functions: 747 regular and 99 mockable.
- 42 functions have no exact dedicated fallback test-method name.
- 27 mockable functions have no exact dedicated handler test-method name.
- 78 whole copied classes; two manual runtime classes are counted separately.
