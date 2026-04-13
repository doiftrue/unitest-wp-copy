<?php
/**
 * Configuration for the WP Copy parser.
 * Filenames and functions to be copied from the WordPress core.
 */

return [
	'wp-includes/class-wp-error.php'                       => 'WP_Error',
	'wp-includes/class-wp-exception.php'                   => 'WP_Exception',
	'wp-includes/class-wp-list-util.php'                   => 'WP_List_Util',
	// Internal hooks implementation; pure callback-list logic.
	'wp-includes/class-wp-hook.php'                        => 'WP_Hook',
	// Base model of a registered script/style dependency.
	'wp-includes/class-wp-dependency.php'                  => '_WP_Dependency',
	// Base dependency manager (queue/dependency graph) for scripts/styles.
	'wp-includes/class-wp-dependencies.php'                => 'WP_Dependencies',
	// JS asset manager built on top of WP_Dependencies.
	'wp-includes/class-wp-scripts.php'                     => 'WP_Scripts',
	// Script Modules in-memory registry and rendering helpers.
	'wp-includes/class-wp-script-modules.php'              => 'WP_Script_Modules',
	// CSS asset manager built on top of WP_Dependencies.
	'wp-includes/class-wp-styles.php'                      => 'WP_Styles',
	// Conditionally suitable: in-memory cache, but has function dependencies (is_multisite/get_current_blog_id/wp_suspend_cache_addition).
	// Without i18n mocks it may enter wp_load_translations_early() (heavy branch).
	'wp-includes/class-wp-object-cache.php'                => 'WP_Object_Cache',
	// Utility for `preg_*` replacement with a callback map (used by formatter/kses).
	'wp-includes/class-wp-matchesmapregex.php'             => 'WP_MatchesMapRegex',
	// gzip/deflate compression helpers; only PHP strings/memory streams.
	'wp-includes/class-wp-http-encoding.php'               => 'WP_Http_Encoding',
	// Cookie parsing/formatting; no I/O.
	'wp-includes/class-wp-http-cookie.php'                 => 'WP_Http_Cookie',
	// Env/constant parsing for proxy settings; no network calls.
	'wp-includes/class-wp-http-proxy.php'                  => 'WP_HTTP_Proxy',
	// Dependency chain for WP_HTML_Tag_Processor (HTML API).
	// These classes must be included together for the Tag Processor to work correctly.
	// - wp-includes/html-api/class-wp-html-attribute-token.php
	// - wp-includes/html-api/class-wp-html-text-replacement.php
	// - wp-includes/html-api/class-wp-html-span.php
	// - wp-includes/html-api/class-wp-html-decoder.php
	// - wp-includes/html-api/class-wp-html-doctype-info.php
	'wp-includes/html-api/class-wp-html-tag-processor.php' => 'WP_HTML_Tag_Processor',
	'wp-includes/html-api/class-wp-html-attribute-token.php' => 'WP_HTML_Attribute_Token',
	'wp-includes/html-api/class-wp-html-text-replacement.php' => 'WP_HTML_Text_Replacement',
	'wp-includes/html-api/class-wp-html-span.php'             => 'WP_HTML_Span',
	'wp-includes/html-api/class-wp-html-decoder.php'          => 'WP_HTML_Decoder',
	'wp-includes/html-api/class-wp-html-doctype-info.php'     => 'WP_HTML_Doctype_Info',
	// Dependency chain for WP_Block_Parser.
	// These classes must be included together; otherwise, the block parser cannot build internal structures.
	// - wp-includes/class-wp-block-parser-block.php
	// - wp-includes/class-wp-block-parser-frame.php
	'wp-includes/class-wp-block-parser.php'                => 'WP_Block_Parser',
	'wp-includes/class-wp-block-parser-block.php'          => 'WP_Block_Parser_Block',
	'wp-includes/class-wp-block-parser-frame.php'          => 'WP_Block_Parser_Frame',
	// NOT IDEAL: pulls REST schema validation (rest_validate_value_from_schema and helper chain).
	// Dependencies can be added, but the chain is large and already outside the "minimal plain PHP" profile.
	'wp-includes/class-wp-block-type.php'                  => 'WP_Block_Type',
	// NOT IDEAL: depends on block-hooks runtime (apply_block_hooks_to_content, get_hooked_blocks, registries)
	// and uses include file paths for pattern content.
	'wp-includes/class-wp-block-patterns-registry.php'     => 'WP_Block_Patterns_Registry',
	// Block styles registry; in-memory.
	'wp-includes/class-wp-block-styles-registry.php'       => 'WP_Block_Styles_Registry',
	// Base abstract walker for tree structures; pure logic.
	'wp-includes/class-wp-walker.php'                      => 'Walker',
	// NOT IDEAL: constructor requires get_privacy_policy_url(), which pulls permalink/post-status dependencies.
	'wp-includes/class-walker-nav-menu.php'                => 'Walker_Nav_Menu',
	// NOT IDEAL: requires post/permalink/date dependency chain (get_post, get_permalink, mysql2date, page_for_posts).
	'wp-includes/class-walker-page.php'                    => 'Walker_Page',
	// NOT IDEAL: requires term/taxonomy dependency chain (get_term_link, get_terms, get_term, get_term_feed_link).
	'wp-includes/class-walker-category.php'                => 'Walker_Category',
	// Partially suitable: registry/state/options API works in memory.
	// Render/admin integration methods still depend on wider wp-admin runtime (user options, meta boxes, current user).
	'wp-admin/includes/class-wp-screen.php'                => 'WP_Screen',
	// (`class-phpass.php` class) portable password hashing; pure PHP.
	'wp-includes/class-phpass.php'                         => 'PasswordHash',
	'wp-includes/class-wp-locale.php'                      => 'WP_Locale',
];
