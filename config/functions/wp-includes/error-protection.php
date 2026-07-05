<?php

return [
	'wp_get_extension_error_description' => '5.2.0',
	'wp_is_fatal_error_handler_enabled'  => '5.2.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_paused_plugins                 // why: constructs the unavailable WP_Paused_Extensions_Storage class
wp_paused_themes                  // why: constructs the unavailable WP_Paused_Extensions_Storage class
wp_register_fatal_error_handler   // why: installs a process shutdown handler backed by the unavailable WP_Fatal_Error_Handler class
wp_recovery_mode                  // why: constructs the unavailable WP_Recovery_Mode class and its recovery lifecycle
*/
