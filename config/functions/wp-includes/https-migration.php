<?php

return [
	'wp_should_replace_insecure_home_url' => '5.7.0',
	'wp_replace_insecure_home_url'        => '5.7.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_update_urls_to_https               // why: requires unavailable option mutation APIs.
wp_update_https_migration_required    // why: requires unavailable option mutation APIs and installation state.
*/
