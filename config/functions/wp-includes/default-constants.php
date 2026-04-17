<?php

return [
	'wp_initial_constants'          => '3.0.0',
	'wp_plugin_directory_constants' => '3.0.0',
	'wp_cookie_constants'           => '3.0.0',
	'wp_ssl_constants'              => '3.0.0',
	'wp_functionality_constants'    => '3.0.0',
];

/*
Not suitable in isolated PHPUnit env (templating/runtime dependency):

wp_templating_constants  // why: get_template_directory() dependency
*/
