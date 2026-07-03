<?php

return [
	'clean_network_cache'  => '4.6.0',
	'update_network_cache' => '4.6.0',
];

/*
Not suitable in isolated PHPUnit env:

get_network           // why: depends on WP_Network::get_instance() (DB)
get_networks          // why: depends on WP_Network_Query (DB)
_prime_network_caches // why: depends on $wpdb
*/
