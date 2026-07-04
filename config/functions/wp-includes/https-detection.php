<?php

return [
	'wp_is_using_https'          => '5.7.0',
	'wp_is_home_url_using_https' => '5.7.0',
	'wp_is_site_url_using_https' => '5.7.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_is_https_supported         // why: delegates to remote HTTPS detection.
wp_get_https_detection_errors // why: performs network requests to the site.
wp_is_local_html_output       // why: REST branch depends on unavailable get_rest_url() runtime chain.
*/
