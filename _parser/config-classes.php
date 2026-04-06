<?php
/**
 * Configuration for the WP Copy parser.
 * Filenames and functions to be copied from the WordPress core.
 */

return [
	'wp-includes/class-wp-error.php'                       => 'WP_Error',
	'wp-includes/class-wp-exception.php'                   => 'WP_Exception',
	'wp-includes/class-wp-list-util.php'                   => 'WP_List_Util',
	// внутренняя реализация хуков; чистая логика работы со списками колбэков.
	'wp-includes/class-wp-hook.php'                        => 'WP_Hook',
	// условно подходит: in-memory кеш, но есть function-deps (is_multisite/get_current_blog_id/wp_suspend_cache_addition).
	// При отсутствии i18n-моков возможен заход в wp_load_translations_early() (тяжелая ветка).
	'wp-includes/class-wp-object-cache.php'                => 'WP_Object_Cache',
	// утилита для `preg_*` замены с callback-картой (используется форматтером/kses).
	'wp-includes/class-wp-matchesmapregex.php'             => 'WP_MatchesMapRegex',
	// gzip/deflate/кандрели сжатия; только PHP-строки/потоки памяти.
	'wp-includes/class-wp-http-encoding.php'               => 'WP_Http_Encoding',
	// парсинг/форматирование cookie; без I/O.
	'wp-includes/class-wp-http-cookie.php'                 => 'WP_Http_Cookie',
	// разбор env/констант для прокси; без сети.
	'wp-includes/class-wp-http-proxy.php'                  => 'WP_HTTP_Proxy',
	// Цепочка зависимостей для WP_HTML_Tag_Processor (HTML API).
	// Эти классы должны идти вместе, чтобы Tag Processor работал корректно.
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
	// Цепочка зависимостей для WP_Block_Parser.
	// Эти классы должны идти вместе, иначе парсер блоков не создаёт внутренние структуры.
	// - wp-includes/class-wp-block-parser-block.php
	// - wp-includes/class-wp-block-parser-frame.php
	'wp-includes/class-wp-block-parser.php'                => 'WP_Block_Parser',
	'wp-includes/class-wp-block-parser-block.php'          => 'WP_Block_Parser_Block',
	'wp-includes/class-wp-block-parser-frame.php'          => 'WP_Block_Parser_Frame',
	// NOT IDEAL: тянет REST-schema валидацию (rest_validate_value_from_schema и цепочку helper-ов).
	// Зависимости можно добавить, но цепочка большая и уже выходит за "минимальный plain PHP" профиль.
	'wp-includes/class-wp-block-type.php'                  => 'WP_Block_Type',
	// NOT IDEAL: зависит от block-hooks рантайма (apply_block_hooks_to_content, get_hooked_blocks, registries)
	// и использует include filePath для контента паттернов.
	'wp-includes/class-wp-block-patterns-registry.php'     => 'WP_Block_Patterns_Registry',
	// реестр стилей блоков; in-memory.
	'wp-includes/class-wp-block-styles-registry.php'       => 'WP_Block_Styles_Registry',
	// базовый — абстрактный обходчик древовидных структур; чистая логика.
	'wp-includes/class-wp-walker.php'                      => 'Walker',
	// NOT IDEAL: в конструкторе нужен get_privacy_policy_url(), который тянет permalink/post-status цепочку.
	'wp-includes/class-walker-nav-menu.php'                => 'Walker_Nav_Menu',
	// NOT IDEAL: требует post/permalink/date цепочки (get_post, get_permalink, mysql2date, page_for_posts).
	'wp-includes/class-walker-page.php'                    => 'Walker_Page',
	// NOT IDEAL: требует term/taxonomy цепочки (get_term_link, get_terms, get_term, get_term_feed_link).
	'wp-includes/class-walker-category.php'                => 'Walker_Category',
	// (класс из `class-phpass.php`) — portable хеширование паролей; чистый PHP.
	'wp-includes/class-phpass.php'                         => 'PasswordHash',
	'wp-includes/class-wp-locale.php'                      => 'WP_Locale',
];
