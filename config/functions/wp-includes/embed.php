<?php

return [
	'wp_maybe_enqueue_oembed_host_js'           => '5.9.0',
	'wp_filter_oembed_iframe_title_attribute'   => '5.2.0',
	'wp_oembed_ensure_format'                   => '4.4.0',
	'_oembed_create_xml'                        => '4.4.0',
	'_oembed_filter_feed_content'               => '4.4.0',
	'wp_embed_handler_audio'                    => '3.6.0',
	'wp_embed_handler_video'                    => '3.6.0',
	'wp_embed_defaults'                         => '2.9.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_embed_register_handler          // why: depends on unavailable WP_Embed runtime
wp_embed_unregister_handler        // why: depends on unavailable WP_Embed runtime
wp_oembed_get                      // why: depends on WP_oEmbed and remote HTTP
_wp_oembed_get_object              // why: depends on unavailable WP_oEmbed class
wp_oembed_add_provider             // why: depends on unavailable WP_oEmbed class
wp_oembed_remove_provider          // why: depends on unavailable WP_oEmbed class
wp_maybe_load_embeds               // why: depends on unavailable WP_Embed runtime
wp_embed_handler_youtube           // why: depends on unavailable WP_Embed runtime
wp_oembed_register_route           // why: depends on unavailable WP_oEmbed_Controller
wp_oembed_add_discovery_links      // why: depends on post query and head rendering lifecycle
wp_oembed_add_host_js              // why: deprecated no-op kept only for hook compatibility
get_post_embed_url                 // why: depends on post retrieval and permalink DB state
get_oembed_endpoint_url            // why: depends on unavailable REST URL runtime
get_post_embed_html                // why: depends on posts and core file I/O
get_oembed_response_data           // why: depends on posts, users, and public-query state
get_oembed_response_data_for_url   // why: depends on multisite and post DB queries
get_oembed_response_data_rich      // why: depends on post and attachment APIs
_oembed_rest_pre_serve_request     // why: depends on REST server and response lifecycle
wp_filter_oembed_result            // why: depends on WP_oEmbed provider and remote HTTP chain
wp_embed_excerpt_more              // why: depends on embed query and current post state
the_excerpt_embed                  // why: depends on current post and template output lifecycle
wp_embed_excerpt_attachment        // why: depends on current attachment metadata and template state
enqueue_embed_scripts              // why: depends on frontend enqueue and query lifecycle
wp_enqueue_embed_styles            // why: depends on frontend style enqueue lifecycle
print_embed_scripts                // why: depends on script printing lifecycle
print_embed_comments_button        // why: depends on comments query and template output lifecycle
print_embed_sharing_button         // why: depends on query and template output lifecycle
print_embed_sharing_dialog         // why: depends on current post and template output lifecycle
the_embed_site_title               // why: depends on site options and template output lifecycle
wp_filter_pre_oembed_result        // why: depends on multisite, post DB, and WP_oEmbed
*/
