<?php

return [
	'wp_initial_constants'          => '',
	'wp_plugin_directory_constants' => '',
	'wp_cookie_constants'           => '',
	'wp_ssl_constants'              => '',
	'wp_functionality_constants'    => '',
];

/*
Not suitable in isolated PHPUnit env (templating/runtime dependency):

wp_templating_constants  // why: get_template_directory() dependency
*/
