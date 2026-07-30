<?php
/**
 * Configuration for the WP Copy parser.
 * Filenames and classes to be copied from the WordPress core.
 */

return [
	'wp-includes/class-wp-error.php'           => [ 'WP_Error' => '2.1.0' ],
	'wp-includes/class-wp-exception.php'       => [ 'WP_Exception' => '6.7.0' ],
	'wp-includes/class-wp-list-util.php'       => [ 'WP_List_Util' => '4.7.0' ],
	// Date-query normalization and SQL generation; all option/function dependencies are deterministic in runtime.
	'wp-includes/class-wp-date-query.php'      => [ 'WP_Date_Query' => '3.7.0' ],
	// Meta-query normalization and SQL generation; uses the runtime's non-querying wpdb adapter.
	'wp-includes/class-wp-meta-query.php'      => [ 'WP_Meta_Query' => '3.2.0' ],
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
	// In-memory HTTP and REST response value objects.
	'wp-includes/class-wp-http-response.php'              => [ 'WP_HTTP_Response' => '4.4.0' ],
	'wp-includes/rest-api/class-wp-rest-response.php'     => [ 'WP_REST_Response' => '4.4.0' ],
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
	// Dependency chain for the full in-memory HTML processor.
	'wp-includes/html-api/class-wp-html-token.php'                      => [ 'WP_HTML_Token' => '6.4.0' ],
	'wp-includes/html-api/class-wp-html-stack-event.php'                => [ 'WP_HTML_Stack_Event' => '6.6.0' ],
	'wp-includes/html-api/class-wp-html-unsupported-exception.php'      => [ 'WP_HTML_Unsupported_Exception' => '6.4.0' ],
	'wp-includes/html-api/class-wp-html-active-formatting-elements.php' => [ 'WP_HTML_Active_Formatting_Elements' => '6.4.0' ],
	'wp-includes/html-api/class-wp-html-open-elements.php'              => [ 'WP_HTML_Open_Elements' => '6.4.0' ],
	'wp-includes/html-api/class-wp-html-processor-state.php'            => [ 'WP_HTML_Processor_State' => '6.4.0' ],
	'wp-includes/html-api/class-wp-html-processor.php'                  => [ 'WP_HTML_Processor' => '6.4.0' ],
	// Balanced-tag helper built entirely on the copied HTML API.
	'wp-includes/interactivity-api/class-wp-interactivity-api-directives-processor.php' => [
		'WP_Interactivity_API_Directives_Processor' => '6.5.0',
	],
	// Dependency chain for WP_Block_Parser.
	// These classes must be included together for the WP_Block_Parser to work correctly:
	// - WP_Block_Parser_Block
	// - WP_Block_Parser_Frame
	'wp-includes/class-wp-block-parser.php'       => [ 'WP_Block_Parser' => '5.0.0' ],
	'wp-includes/class-wp-block-parser-block.php' => [ 'WP_Block_Parser_Block' => '5.0.0' ],
	'wp-includes/class-wp-block-parser-frame.php' => [ 'WP_Block_Parser_Frame' => '5.0.0' ],
	'wp-includes/class-wp-block-type.php'         => [ 'WP_Block_Type' => '5.0.0' ],
	// Block editor/template data holders; no live editor or template lifecycle.
	'wp-includes/class-wp-block-editor-context.php' => [ 'WP_Block_Editor_Context' => '5.8.0' ],
	'wp-includes/class-wp-block-template.php'       => [ 'WP_Block_Template' => '5.8.0' ],
	// In-memory registry used by block type lookup helpers.
	'wp-includes/class-wp-block-type-registry.php' => [ 'WP_Block_Type_Registry' => '5.0.0' ],
	// In-memory Block Bindings source model and registry.
	'wp-includes/class-wp-block-bindings-source.php'   => [ 'WP_Block_Bindings_Source' => '6.5.0' ],
	'wp-includes/class-wp-block-bindings-registry.php' => [ 'WP_Block_Bindings_Registry' => '6.5.0' ],
	// Block styles registry; in-memory.
	'wp-includes/class-wp-block-styles-registry.php'   => [ 'WP_Block_Styles_Registry' => '5.3.0' ],
	// Block pattern categories registry; in-memory.
	'wp-includes/class-wp-block-pattern-categories-registry.php' => [ 'WP_Block_Pattern_Categories_Registry' => '5.5.0' ],
	// Speculation rules validation and JSON serialization; pure in-memory state.
	'wp-includes/class-wp-speculation-rules.php' => [ 'WP_Speculation_Rules' => '6.8.0' ],
	// Compact immutable lookup structure; pure PHP strings and arrays.
	'wp-includes/class-wp-token-map.php' => [ 'WP_Token_Map' => '6.6.0' ],
	// Style Engine classes form a dependency-complete in-memory CSS pipeline.
	'wp-includes/style-engine/class-wp-style-engine-css-declarations.php' => [
		'WP_Style_Engine_CSS_Declarations' => '6.1.0',
	],
	'wp-includes/style-engine/class-wp-style-engine-css-rule.php' => [
		'WP_Style_Engine_CSS_Rule' => '6.1.0',
	],
	'wp-includes/style-engine/class-wp-style-engine-css-rules-store.php' => [
		'WP_Style_Engine_CSS_Rules_Store' => '6.1.0',
	],
	'wp-includes/style-engine/class-wp-style-engine-processor.php' => [
		'WP_Style_Engine_Processor' => '6.1.0',
	],
	'wp-includes/style-engine/class-wp-style-engine.php' => [ 'WP_Style_Engine' => '6.1.0' ],
	// Font value sanitization and normalization helpers; no file or network access.
	'wp-includes/fonts/class-wp-font-utils.php' => [ 'WP_Font_Utils' => '6.5.0' ],
	// Abilities value objects execute supplied callbacks against copied schema validation helpers.
	'wp-includes/abilities-api/class-wp-ability.php'          => [ 'WP_Ability' => '6.9.0' ],
	'wp-includes/abilities-api/class-wp-ability-category.php' => [ 'WP_Ability_Category' => '6.9.0' ],
	// In-memory POMO models, plural evaluator, translation catalog, and string reader.
	'wp-includes/pomo/entry.php' => [ 'Translation_Entry' => '2.8.0' ],
	'wp-includes/pomo/plural-forms.php' => [ 'Plural_Forms' => '4.9.0' ],
	'wp-includes/pomo/translations.php' => [
		'Translations'         => '2.8.0',
		'Gettext_Translations' => '2.8.0',
		'NOOP_Translations'    => '2.8.0',
	],
	'wp-includes/pomo/streams.php' => [
		'POMO_Reader'       => '2.8.0',
		'POMO_StringReader' => '2.8.0',
	],
	// Base abstract walker for tree structures; pure logic.
	'wp-includes/class-wp-walker.php'                  => [ 'Walker' => '2.1.0' ],
	// NOT IDEAL: registry/state/options API works in memory.
	// Render/admin integration methods still depend on wider wp-admin runtime (user options, meta boxes, current user).
	'wp-admin/includes/class-wp-screen.php'            => [ 'WP_Screen' => '4.4.0' ],
	// (`class-phpass.php` class) portable password hashing; pure PHP.
	'wp-includes/class-phpass.php'                     => [ 'PasswordHash' => '2.5.0' ],
	'wp-includes/class-wp-locale.php'                  => [ 'WP_Locale' => '4.6.0' ],
	// Sitemap registry/base/index helpers and XML builder.
	'wp-includes/sitemaps/class-wp-sitemaps-registry.php' => [ 'WP_Sitemaps_Registry' => '5.5.0' ],
	'wp-includes/sitemaps/class-wp-sitemaps-provider.php' => [ 'WP_Sitemaps_Provider' => '5.5.0' ],
	'wp-includes/sitemaps/class-wp-sitemaps-index.php'    => [ 'WP_Sitemaps_Index' => '5.5.0' ],
	'wp-includes/sitemaps/class-wp-sitemaps-renderer.php' => [ 'WP_Sitemaps_Renderer' => '5.5.0' ],
	// In-memory connector registry; default connector init remains disabled.
	'wp-includes/class-wp-connector-registry.php'         => [ 'WP_Connector_Registry' => '7.0.0' ],
	// In-memory widget-object registry; full WP_Widget lifecycle remains out of scope.
	'wp-includes/class-wp-widget-factory.php'             => [ 'WP_Widget_Factory' => '2.8.0' ],
];

/*
Not suitable in isolated PHPUnit env:

WP_Block_Patterns_Registry // why: discussion required for block-hooks boundary and pattern file loading.
Walker_Page                // why: NOT IDEAL: requires post/permalink/date dependency chain (get_post/get_permalink/mysql2date/page_for_posts).
Walker_Category            // why: NOT IDEAL: requires term/taxonomy dependency chain (get_term_link/get_terms/get_term/get_term_feed_link).
Walker_Nav_Menu            // why: NOT IDEAL: depends on get_privacy_policy_url() and nav-menu runtime chain.
WP_Sitemaps_Posts          // why: depends on WP_Query/get_permalink and post-query runtime chain.
WP_Sitemaps_Taxonomies     // why: depends on WP_Term_Query/get_term_link/wp_count_terms runtime chain.
WP_Sitemaps_Users          // why: depends on WP_User_Query/get_author_posts_url runtime chain.
WP_Sitemaps_Stylesheet     // why: depends on get_language_attributes() and HTTP output lifecycle.
WP_Fatal_Error_Handler     // why: depends on maintenance/recovery/wp_die shutdown request lifecycle.
*/
