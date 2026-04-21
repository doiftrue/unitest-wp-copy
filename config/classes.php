<?php
/**
 * Configuration for the WP Copy parser.
 * Filenames and classes to be copied from the WordPress core.
 */

return [
	'wp-includes/class-wp-error.php'           => [ 'WP_Error' => '2.1.0' ],
	'wp-includes/class-wp-exception.php'       => [ 'WP_Exception' => '6.7.0' ],
	'wp-includes/class-wp-list-util.php'       => [ 'WP_List_Util' => '4.7.0' ],
	// Internal hooks implementation; pure callback-list logic.
	'wp-includes/class-wp-hook.php'            => [ 'WP_Hook' => '4.7.0' ],
	// Base model of a registered script/style dependency.
	'wp-includes/class-wp-dependency.php'      => [ '_WP_Dependency' => '4.7.0' ],
	// Base dependency manager (queue/dependency graph) for scripts/styles.
	'wp-includes/class-wp-dependencies.php'    => [ 'WP_Dependencies' => '2.6.0' ],
	// JS asset manager built on top of WP_Dependencies.
	'wp-includes/class-wp-scripts.php'         => [ 'WP_Scripts' => '2.6.0' ],
	// Script Modules in-memory registry and rendering helpers.
	'wp-includes/class-wp-script-modules.php'  => [ 'WP_Script_Modules' => '6.5.0' ],
	// CSS asset manager built on top of WP_Dependencies.
	'wp-includes/class-wp-styles.php'          => [ 'WP_Styles' => '2.6.0' ],
	// Conditionally suitable: in-memory cache, but has function dependencies (is_multisite/get_current_blog_id/wp_suspend_cache_addition).
	// Without i18n mocks it may enter wp_load_translations_early() (heavy branch).
	'wp-includes/class-wp-object-cache.php'    => [ 'WP_Object_Cache' => '5.4.0' ],
	// Utility for `preg_*` replacement with a callback map (used by formatter/kses).
	'wp-includes/class-wp-matchesmapregex.php' => [ 'WP_MatchesMapRegex' => '4.7.0' ],
	// gzip/deflate compression helpers; only PHP strings/memory streams.
	'wp-includes/class-wp-http-encoding.php'   => [ 'WP_Http_Encoding' => '4.4.0' ],
	// Cookie parsing/formatting; no I/O.
	'wp-includes/class-wp-http-cookie.php'     => [ 'WP_Http_Cookie' => '4.4.0' ],
	// Env/constant parsing for proxy settings; no network calls.
	'wp-includes/class-wp-http-proxy.php'      => [ 'WP_HTTP_Proxy' => '4.4.0' ],
	// Dependency chain for WP_HTML_Tag_Processor (HTML API).
	// These classes must be included together for the WP_HTML_Tag_Processor to work correctly:
	// - WP_HTML_Attribute_Token
	// - WP_HTML_Text_Replacement
	// - WP_HTML_Span
	// - WP_HTML_Decoder
	// - WP_HTML_Doctype_Info
	'wp-includes/html-api/class-wp-html-tag-processor.php'    => [ 'WP_HTML_Tag_Processor' => '6.2.0' ],
	'wp-includes/html-api/class-wp-html-attribute-token.php'  => [ 'WP_HTML_Attribute_Token' => '6.2.0' ],
	'wp-includes/html-api/class-wp-html-text-replacement.php' => [ 'WP_HTML_Text_Replacement' => '6.2.0' ],
	'wp-includes/html-api/class-wp-html-span.php'             => [ 'WP_HTML_Span' => '6.2.0' ],
	'wp-includes/html-api/class-wp-html-decoder.php'          => [ 'WP_HTML_Decoder' => '6.6.0' ],
	'wp-includes/html-api/class-wp-html-doctype-info.php'     => [ 'WP_HTML_Doctype_Info' => '6.7.0' ],
	// Dependency chain for WP_Block_Parser.
	// These classes must be included together for the WP_Block_Parser to work correctly:
	// - WP_Block_Parser_Block
	// - WP_Block_Parser_Frame
	'wp-includes/class-wp-block-parser.php'       => [ 'WP_Block_Parser' => '5.0.0' ],
	'wp-includes/class-wp-block-parser-block.php' => [ 'WP_Block_Parser_Block' => '5.0.0' ],
	'wp-includes/class-wp-block-parser-frame.php' => [ 'WP_Block_Parser_Frame' => '5.0.0' ],
	'wp-includes/class-wp-block-type.php'         => [ 'WP_Block_Type' => '5.0.0' ],
	// Block styles registry; in-memory.
	'wp-includes/class-wp-block-styles-registry.php'   => [ 'WP_Block_Styles_Registry' => '5.3.0' ],
	// Base abstract walker for tree structures; pure logic.
	'wp-includes/class-wp-walker.php'                  => [ 'Walker' => '2.1.0' ],
	// NOT IDEAL: registry/state/options API works in memory.
	// Render/admin integration methods still depend on wider wp-admin runtime (user options, meta boxes, current user).
	'wp-admin/includes/class-wp-screen.php'            => [ 'WP_Screen' => '4.4.0' ],
	// (`class-phpass.php` class) portable password hashing; pure PHP.
	'wp-includes/class-phpass.php'                     => [ 'PasswordHash' => '2.5.0' ],
	'wp-includes/class-wp-locale.php'                  => [ 'WP_Locale' => '4.6.0' ],
];

/*
Not suitable in isolated PHPUnit env:

WP_Block_Patterns_Registry  // why: NOT IDEAL: depends on block-hooks runtime (apply_block_hooks_to_content/get_hooked_blocks) and pattern file include paths.
Walker_Page  // why: NOT IDEAL: requires post/permalink/date dependency chain (get_post/get_permalink/mysql2date/page_for_posts).
Walker_Category  // why: NOT IDEAL: requires term/taxonomy dependency chain (get_term_link/get_terms/get_term/get_term_feed_link).
Walker_Nav_Menu  // why: NOT IDEAL: depends on get_privacy_policy_url() and nav-menu runtime chain.
*/
