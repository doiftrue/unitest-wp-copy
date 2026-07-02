<?php

return [
	'wp_maybe_grant_resume_extensions_caps' => '5.2.0',
	'wp_maybe_grant_install_languages_cap'  => '4.9.0',
];

/*
Not suitable in isolated PHPUnit env:

map_meta_cap              // why: massive switch; depends on get_post, get_option, get_post_type_object + DB
current_user_can          // why: depends on wp_get_current_user (DB)
current_user_can_for_site // why: depends on current_user_can (DB)
author_can                // why: depends on get_post + get_userdata (DB)
user_can                  // why: depends on get_userdata (DB) + WP_User::has_cap
user_can_for_site         // why: depends on get_userdata + user_can (DB)
wp_roles                  // why: depends on WP_Roles class (DB-backed role storage)
get_role                  // why: depends on wp_roles() (DB)
add_role                  // why: depends on wp_roles() (DB)
remove_role               // why: depends on wp_roles() (DB)
get_super_admins          // why: depends on get_site_option (DB)
is_super_admin            // why: depends on wp_get_current_user / get_userdata (DB)
grant_super_admin         // why: depends on get_site_option + update_site_option (DB)
revoke_super_admin        // why: depends on get_site_option + update_site_option (DB)
wp_maybe_grant_site_health_caps // why: depends on is_super_admin → get_userdata (DB)
*/
