<?php

return [
	'wp_login_url'                   => '2.7.0',
	'wp_registration_url'            => '3.6.0',
	'wp_login_form'                  => '3.0.0',
	'wp_meta'                        => '1.5.0',
	'bloginfo'                       => '0.71',
	'get_archives_link'              => '1.0.0',
	'calendar_week_mod'              => '1.5.0',
	'allowed_tags'                   => '1.0.1',
	'wp_head'                        => '1.2.0',
	'wp_footer'                      => '1.5.1',
	'wp_body_open'                   => '5.2.0',
	'rsd_link'                       => '2.0.0',
	'wp_strict_cross_origin_referrer'=> '5.7.0',
	'wp_resource_hints'              => '4.6.0',
	'wp_preload_resources'           => '6.1.0',
	'wp_dependencies_unique_hosts'   => '4.6.0',
	'get_language_attributes'        => '4.3.0',
	'language_attributes'            => '2.1.0',
	'wp_admin_css_color'             => '2.5.0',
	'checked'                        => '1.0.0',
	'selected'                       => '1.0.0',
	'disabled'                       => '3.0.0',
	'wp_readonly'                    => '5.9.0',
	'__checked_selected_helper'      => '2.8.0',
	'wp_required_field_indicator'    => '6.1.0',
	'wp_required_field_message'      => '6.1.0',
];

/*
Custom mocks:
get_bloginfo  // why: mocked
*/


/*
Not suitable in isolated PHPUnit env:

get_header                  // why: template file resolution/loading dependency (`locate_template` + filesystem).
get_footer                  // why: template file resolution/loading dependency (`locate_template` + filesystem).
get_sidebar                 // why: template file resolution/loading dependency (`locate_template` + filesystem).
get_template_part           // why: template file resolution/loading dependency (`locate_template` + filesystem).
get_search_form             // why: template loading + missing runtime query helpers (`locate_template`/`get_search_query` chain).
wp_loginout                 // why: auth/session dependency (`is_user_logged_in`).
wp_logout_url               // why: nonce/auth dependency (`wp_nonce_url`/`wp_create_nonce` chain).
wp_lostpassword_url         // why: multisite dependency (`network_site_url`/`is_multisite`/`get_site`).
wp_register                 // why: auth/capability + admin URL dependency (`is_user_logged_in`/`current_user_can`/`admin_url`).
get_site_icon_url           // why: media/attachments + multisite runtime dependency.
site_icon_url               // why: depends on `get_site_icon_url()` media/attachments runtime chain.
has_site_icon               // why: option/media runtime dependency (`get_site_icon_url` chain).
has_custom_logo             // why: customizer/theme-mod runtime dependency.
get_custom_logo             // why: customizer/theme-mod + media + query context dependency.
the_custom_logo             // why: depends on `get_custom_logo()` customizer/theme runtime chain.
wp_get_document_title       // why: query-context conditionals dependency (`is_*` + queried object chain).
_wp_render_title_tag        // why: depends on `wp_get_document_title()` query context chain.
wp_title                    // why: query-context + archive object dependency (`get_query_var`/`is_*` chain).
single_post_title           // why: queried object dependency (`get_queried_object`).
post_type_archive_title     // why: query/archive dependency (`is_post_type_archive` + query vars).
single_cat_title            // why: depends on `single_term_title()` queried taxonomy runtime chain.
single_tag_title            // why: depends on `single_term_title()` queried taxonomy runtime chain.
single_term_title           // why: queried taxonomy runtime dependency (`get_queried_object`/`is_tax` chain).
single_month_title          // why: query vars + locale runtime dependency.
the_archive_title           // why: depends on `get_the_archive_title()` queried object runtime chain.
get_the_archive_title       // why: queried object + archive conditionals runtime dependency.
the_archive_description     // why: depends on archive description runtime (`term_description`/author context).
get_the_archive_description // why: queried object + term/author runtime dependency.
get_the_post_type_description // why: query vars + post-type object runtime dependency.
wp_get_archives             // why: DB/query/cache runtime dependency (`wpdb`, query conditionals, cache API).
get_calendar                // why: DB/query/cache + loop globals runtime dependency.
delete_get_calendar_cache   // why: depends on cache API wrappers (`wp_cache_delete`) not provided by this runtime.
the_date_xml                // why: post loop dependency (`get_post` / current loop state).
the_date                    // why: post loop/day-state dependency (`is_new_day` + loop globals).
get_the_date                // why: post object dependency (`get_post` chain).
the_modified_date           // why: depends on post retrieval/time chain (`get_the_modified_date`).
get_the_modified_date       // why: post object dependency (`get_post` chain).
the_time                    // why: depends on post retrieval/time chain (`get_the_time`).
get_the_time                // why: post object dependency (`get_post` chain).
get_post_time               // why: post object dependency (`get_post` chain).
get_post_datetime           // why: post object dependency (`get_post` chain).
get_post_timestamp          // why: depends on `get_post_datetime()` post-object chain.
the_modified_time           // why: depends on post retrieval/time chain (`get_the_modified_time`).
get_the_modified_time       // why: post object dependency (`get_post` chain).
get_post_modified_time      // why: post object dependency (`get_post` chain).
the_weekday                 // why: post loop dependency (`get_post` + loop globals).
the_weekday_date            // why: post loop/day-state dependency (`get_post` + loop globals).
feed_links                  // why: feed/query runtime dependency (`get_feed_link`/feed context).
feed_links_extra            // why: queried object + feed/query runtime dependency.
wp_site_icon                // why: depends on site-icon/media/customizer runtime chain.
user_can_richedit           // why: user/session/browser capability dependency (`get_user_option`/auth state).
wp_default_editor           // why: user/session setting dependency (`wp_get_current_user`/`get_user_setting`).
wp_editor                   // why: requires editor bootstrap/class file include runtime.
wp_enqueue_editor           // why: requires editor bootstrap/class file include runtime.
wp_enqueue_code_editor      // why: user/capability/editor assets runtime dependency.
wp_get_code_editor_settings // why: capability + MIME/editor runtime dependency.
get_search_query            // why: query-var dependency (`get_query_var`).
the_search_query            // why: depends on `get_search_query()` query-var runtime chain.
paginate_links              // why: query/rewrite runtime dependency (`get_pagenum_link`/`get_query_var`/rewrite globals).
register_admin_color_schemes // why: admin URL dependency (`admin_url`) + admin runtime context.
wp_admin_css_uri            // why: admin URL dependency (`admin_url`) not available in runtime.
wp_admin_css                // why: depends on `wp_admin_css_uri()` admin URL/runtime chain.
add_thickbox                // why: admin context dependency (`is_network_admin` + admin hooks).
wp_generator                // why: depends on generator/feed helpers not fully available (`get_the_generator` chain).
the_generator               // why: depends on `get_the_generator()` feed helper chain.
get_the_generator           // why: feed helper dependency (`get_bloginfo_rss`) not available in runtime.
wp_heartbeat_settings       // why: auth/admin nonce dependency (`is_user_logged_in`/`wp_create_nonce`/`admin_url`).
*/
