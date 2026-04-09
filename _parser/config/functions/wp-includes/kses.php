<?php

return [
	'wp_kses'                             => '',
	'wp_kses_one_attr'                    => '',
	'wp_kses_allowed_html'                => '',
	'wp_kses_hook'                        => '',
	'wp_kses_version'                     => '',
	'wp_kses_split'                       => '',
	'wp_kses_uri_attributes'              => '',
	'_wp_kses_split_callback'             => '',
	'wp_kses_split2'                      => '',
	'wp_kses_attr'                        => '',
	'wp_kses_attr_check'                  => '',
	'wp_kses_hair'                        => '',
	'wp_kses_attr_parse'                  => '',
	'wp_kses_hair_parse'                  => '',
	'wp_kses_check_attr_val'              => '',
	'wp_kses_bad_protocol'                => '',
	'wp_kses_no_null'                     => '',
	'wp_kses_stripslashes'                => '',
	'wp_kses_array_lc'                    => '',
	'wp_kses_html_error'                  => '',
	'wp_kses_bad_protocol_once'           => '',
	'wp_kses_bad_protocol_once2'          => '',
	'wp_kses_normalize_entities'          => '',
	'wp_kses_named_entities'              => '',
	'wp_kses_xml_named_entities'          => '',
	'wp_kses_normalize_entities2'         => '',
	'wp_kses_normalize_entities3'         => '',
	'valid_unicode'                       => '',
	'wp_kses_decode_entities'             => '',
	'_wp_kses_decode_entities_chr'        => '',
	'_wp_kses_decode_entities_chr_hexdec' => '',
	'wp_filter_kses'                      => '', // current_filter() dependency
	'wp_kses_data'                        => '', // current_filter() dependency
	'wp_filter_post_kses'                 => '',
	'wp_filter_global_styles_post'        => '',
	'wp_kses_post'                        => '',
	'wp_kses_post_deep'                   => '',
	'wp_filter_nohtml_kses'               => '',
	// 'kses_init'                           => '', // why: depends on current_user_can() runtime capability checks.
	// 'kses_init_filters'                   => '', // why: depends on current_user_can() runtime capability checks.
	// 'kses_remove_filters'                 => '', // make no sense
	'safecss_filter_attr'                 => '',
	'_wp_add_global_attributes'           => '',
	// '_wp_kses_allow_pdf_objects'          => '', // wp_upload_dir() dependency
];
