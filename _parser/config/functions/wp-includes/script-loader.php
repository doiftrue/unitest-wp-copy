<?php

return [
	// Минимальные helper-функции, которые требуются WP_Scripts при выводе тегов.
	'wp_sanitize_script_attributes' => '',
	'wp_get_script_tag'             => '',
	'wp_print_script_tag'           => '',
	'wp_get_inline_script_tag'      => '',
	'wp_print_inline_script_tag'    => '',
	'_print_scripts'                => '',
	// Missing in config, kept for visibility because not suitable for isolated PHPUnit env:
	// 'wp_maybe_inline_styles'        => '', // why: читает CSS-файлы по абсолютному пути (filesystem runtime dependency).
];
