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
	// базовый объектный кеш (память процесса), без внешних бекендов.
	'wp-includes/class-wp-object-cache.php'                => 'WP_Object_Cache',
	// утилита для `preg_*` замены с callback-картой (используется форматтером/kses).
	'wp-includes/class-wp-matchesmapregex.php'             => 'WP_MatchesMapRegex',
	// gzip/deflate/кандрели сжатия; только PHP-строки/потоки памяти.
	'wp-includes/class-wp-http-encoding.php'               => 'WP_Http_Encoding',
	// парсинг/форматирование cookie; без I/O.
	'wp-includes/class-wp-http-cookie.php'                 => 'WP_Http_Cookie',
	// разбор env/констант для прокси; без сети.
	'wp-includes/class-wp-http-proxy.php'                  => 'WP_HTTP_Proxy',
	//  связанные маленькие helper-классы) — парсер/редактор атрибутов HTML-тегов; чистый PHP.
	'wp-includes/html-api/class-wp-html-tag-processor.php' => 'WP_HTML_Tag_Processor',
	// `WP_Block_Parser_Block` — парсер блоков Gutenberg (разбор на структуру), без файлов и БД.
	'wp-includes/class-wp-block-parser.php'                => 'WP_Block_Parser',
	// `WP_Block_Type_Registry` — регистрация/метаданные блоков; in-memory реестр.
	'wp-includes/class-wp-block-type.php'                  => 'WP_Block_Type',
	// реестр паттернов блоков; in-memory.
	'wp-includes/class-wp-block-patterns-registry.php'     => 'WP_Block_Patterns_Registry',
	// реестр стилей блоков; in-memory.
	'wp-includes/class-wp-block-styles-registry.php'       => 'WP_Block_Styles_Registry',
	// базовый — абстрактный обходчик древовидных структур; чистая логика.
	'wp-includes/class-wp-walker.php'                      => 'Walker',
	'wp-includes/class-walker-nav-menu.php'                => 'Walker_Nav_Menu',
	'wp-includes/class-walker-page.php'                    => 'Walker_Page',
	'wp-includes/class-walker-category.php'                => 'Walker_Category',
	// (класс из `class-phpass.php`) — portable хеширование паролей; чистый PHP.
	'wp-includes/class-phpass.php'                         => 'PasswordHash',
	'wp-includes/class-wp-locale.php'                      => 'WP_Locale',
];

