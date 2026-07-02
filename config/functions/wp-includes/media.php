<?php

return [
	'wp_img_tag_add_auto_sizes'                 => '6.7.0',
	'wp_sizes_attribute_includes_valid_auto'    => '6.7.0',
	'wp_get_image_editor_output_format'         => '6.7.0',
	'_wp_post_thumbnail_context_filter'         => '6.3.0',
	'_wp_post_thumbnail_context_filter_add'     => '6.3.0',
	'_wp_post_thumbnail_context_filter_remove'  => '6.3.0',
	'wp_maybe_add_fetchpriority_high_attr'      => '6.3.0',
	'wp_high_priority_element_flag'             => '6.3.0',
	'wp_omit_loading_attr_threshold'            => '5.9.0',
	'wp_increase_content_media_count'           => '5.9.0',
	'wp_image_file_matches_image_meta'          => '5.5.0',
	'wp_image_src_get_dimensions'               => '5.5.0',
	'wp_lazy_loading_enabled'                   => '5.5.0',
	'_wp_add_additional_image_sizes'            => '5.3.0',
	'wp_get_registered_image_subsizes'          => '5.3.0',
	'wp_get_additional_image_sizes'             => '4.7.0 mockable',
	'wp_image_matches_ratio'                    => '4.6.0',
	'_wp_get_attachment_relative_path'          => '4.4.1',
	'_wp_get_image_size_from_meta'              => '4.4.0',
	'has_image_size'                            => '3.9.0',
	'remove_image_size'                         => '3.9.0',
	'wp_get_attachment_id3_keys'                => '3.9.0',
	'wp_mediaelement_fallback'                  => '3.6.0',
	'wp_get_audio_extensions'                   => '3.6.0',
	'wp_get_video_extensions'                   => '3.6.0',
	'get_intermediate_image_sizes'              => '3.0.0',
	'_wp_post_thumbnail_class_filter'           => '2.9.0',
	'_wp_post_thumbnail_class_filter_add'       => '2.9.0',
	'_wp_post_thumbnail_class_filter_remove'    => '2.9.0',
	'wp_expand_dimensions'                      => '2.9.0',
	'wp_max_upload_size'                        => '2.5.0',
	'wp_constrain_dimensions'                   => '2.5.0',
	'image_resize_dimensions'                   => '2.5.0',
	'get_media_embedded_in_content'             => '3.6.0',
	'add_image_size'                            => '2.9.0',
	'set_post_thumbnail_size'                   => '2.9.0',
	'image_constrain_size_for_editor'           => '2.5.0',
	'image_hwstring'                            => '2.5.0',
];

/*
Not suitable in isolated PHPUnit env:

image_downsize                         // why: depends on attachment metadata and files
get_image_tag                          // why: depends on image_downsize attachment chain
image_make_intermediate_size           // why: depends on WP_Image_Editor and filesystem writes
image_get_intermediate_size            // why: depends on attachment metadata and upload paths
wp_get_attachment_image_src            // why: depends on attachment metadata and post APIs
wp_get_attachment_image                // why: depends on attachment metadata and post APIs
wp_get_attachment_image_url            // why: depends on wp_get_attachment_image_src attachment chain
wp_get_attachment_image_srcset         // why: depends on attachment metadata
wp_calculate_image_srcset              // why: depends on uploads, attachment metadata, and files
wp_get_attachment_image_sizes          // why: depends on attachment metadata
wp_calculate_image_sizes               // why: string-size branch depends on attachment metadata
wp_image_add_srcset_and_sizes          // why: depends on attachment metadata and srcset chain
wp_filter_content_tags                 // why: depends on attachment lookup and full content lifecycle
wp_enqueue_img_auto_sizes_contain_css_fix // why: depends on stylesheet enqueue lifecycle
wp_img_tag_add_loading_optimization_attrs // why: depends on query and request lifecycle
wp_img_tag_add_width_and_height_attr    // why: depends on attachment metadata
wp_img_tag_add_srcset_and_sizes_attr    // why: depends on attachment metadata and srcset chain
wp_iframe_tag_add_loading_attr          // why: depends on request-context optimization chain
img_caption_shortcode                  // why: depends on shortcode and content rendering lifecycle
gallery_shortcode                      // why: depends on post queries and attachment metadata
wp_underscore_playlist_templates       // why: prints admin media templates
wp_playlist_scripts                    // why: depends on script/style enqueue lifecycle
wp_playlist_shortcode                  // why: depends on post queries, metadata, and enqueue lifecycle
wp_audio_shortcode                     // why: depends on post state and script enqueue lifecycle
wp_video_shortcode                     // why: depends on post state and script enqueue lifecycle
get_previous_image_link                // why: depends on adjacent attachment DB query
previous_image_link                    // why: depends on adjacent attachment DB query
get_next_image_link                    // why: depends on adjacent attachment DB query
next_image_link                        // why: depends on adjacent attachment DB query
get_adjacent_image_link                // why: depends on get_children DB query
adjacent_image_link                    // why: depends on get_adjacent_image_link DB chain
get_attachment_taxonomies             // why: depends on post and taxonomy registries
get_taxonomies_for_attachments         // why: depends on taxonomy registry state
is_gd_image                            // why: positive behavior requires unavailable GD runtime
wp_imagecreatetruecolor                // why: requires the optional GD runtime
wp_get_image_editor                    // why: depends on WP_Image_Editor implementations and filesystem
wp_image_editor_supports               // why: depends on WP_Image_Editor implementations
_wp_image_editor_choose                // why: depends on editor classes, cache, and filesystem capabilities
wp_plupload_default_settings           // why: depends on nonce, multisite, and admin enqueue lifecycle
wp_prepare_attachment_for_js           // why: depends on posts, users, metadata, and admin APIs
wp_enqueue_media                       // why: depends on admin request and enqueue lifecycle
get_attached_media                     // why: depends on get_children DB query
get_post_galleries                     // why: depends on post retrieval and attachment expansion
get_post_gallery                       // why: depends on get_post_galleries DB chain
get_post_galleries_images              // why: depends on get_post_galleries DB chain
get_post_gallery_images                // why: depends on get_post_gallery DB chain
wp_maybe_generate_attachment_metadata  // why: depends on attachment DB state and filesystem
attachment_url_to_postid               // why: performs direct database queries
wpview_media_sandbox_styles            // why: depends on frontend style enqueue lifecycle
wp_register_media_personal_data_exporter // why: registers a DB-backed exporter callback
wp_media_personal_data_exporter        // why: depends on user and attachment DB queries
wp_show_heic_upload_error              // why: depends on image editor capability runtime
wp_getimagesize                        // why: performs image filesystem I/O
wp_get_avif_info                       // why: performs filesystem I/O and loads AVIF parser
wp_get_webp_info                       // why: performs image filesystem I/O
wp_get_loading_optimization_attributes // why: depends on WP_Query and request lifecycle
*/
