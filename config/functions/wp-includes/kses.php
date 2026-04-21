<?php

return [
	'wp_kses'                             => '1.0.0',
	'wp_kses_one_attr'                    => '4.2.3',
	'wp_kses_allowed_html'                => '3.5.0',
	'wp_kses_hook'                        => '1.0.0',
	'wp_kses_version'                     => '1.0.0',
	'wp_kses_split'                       => '1.0.0',
	'wp_kses_uri_attributes'              => '5.0.1',
	'_wp_kses_split_callback'             => '3.1.0',
	'wp_kses_split2'                      => '1.0.0',
	'wp_kses_attr'                        => '1.0.0',
	'wp_kses_attr_check'                  => '4.2.3',
	'wp_kses_hair'                        => '1.0.0',
	'wp_kses_attr_parse'                  => '4.2.3',
	'wp_kses_hair_parse'                  => '4.2.3',
	'wp_kses_check_attr_val'              => '1.0.0',
	'wp_kses_bad_protocol'                => '1.0.0',
	'wp_kses_no_null'                     => '1.0.0',
	'wp_kses_stripslashes'                => '1.0.0',
	'wp_kses_array_lc'                    => '1.0.0',
	'wp_kses_html_error'                  => '1.0.0',
	'wp_kses_bad_protocol_once'           => '1.0.0',
	'wp_kses_bad_protocol_once2'          => '1.0.0',
	'wp_kses_normalize_entities'          => '1.0.0',
	'wp_kses_named_entities'              => '3.0.0',
	'wp_kses_xml_named_entities'          => '5.5.0',
	'wp_kses_normalize_entities2'         => '1.0.0',
	'wp_kses_normalize_entities3'         => '2.7.0',
	'valid_unicode'                       => '2.7.0',
	'wp_kses_decode_entities'             => '1.0.0',
	'_wp_kses_decode_entities_chr'        => '2.9.0',
	'_wp_kses_decode_entities_chr_hexdec' => '2.9.0',
	'wp_filter_kses'                      => '1.0.0',
	'wp_kses_data'                        => '2.9.0',
	'wp_filter_post_kses'                 => '2.0.0',
	'wp_filter_global_styles_post'        => '5.9.0',
	'wp_kses_post'                        => '2.9.0',
	'wp_kses_post_deep'                   => '4.4.2',
	'wp_filter_nohtml_kses'               => '2.1.0',
	'safecss_filter_attr'                 => '2.8.1',
	'_wp_add_global_attributes'           => '3.5.0',
];

/*
Not suitable in isolated PHPUnit env:

kses_init                  // why: depends on current_user_can() runtime capability checks.
kses_init_filters          // why: depends on current_user_can() runtime capability checks.
kses_remove_filters        // why: make no sense
_wp_kses_allow_pdf_objects // why: wp_upload_dir() dependency
*/
