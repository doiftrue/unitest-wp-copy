<?php

return [
	'is_subdomain_install' => '3.0.0 mockable',
];

/*
Not suitable in isolated PHPUnit env:

wp_get_active_network_plugins    // why: depends on file_exists() filesystem check
ms_site_check                    // why: depends on is_super_admin(), get_site() (DB), wp_die(), file_exists()
get_network_by_path              // why: depends on WP_Network::get_by_path() (DB)
get_site_by_path                 // why: depends on get_sites() (DB/WP_Site_Query)
ms_load_current_site_and_network // why: heavy multisite bootstrap, depends on DB/WP_Network/WP_Site
ms_not_installed                 // why: depends on $wpdb, wp_die()
get_current_site_name            // why: deprecated 3.9.0
wpmu_current_site                // why: deprecated 3.9.0
wp_get_network                   // why: deprecated 4.7.0, depends on get_network() (DB)
*/
