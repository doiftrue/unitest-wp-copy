<?php

return [
	'wp_is_connector_registered'  => '7.0.0 mockable',
	'wp_get_connector'            => '7.0.0 mockable',
	'wp_get_connectors'           => '7.0.0 mockable',
	'_wp_connectors_mask_api_key' => '7.0.0',
];

/*
Not suitable in isolated PHPUnit env:

_wp_connectors_resolve_ai_provider_logo_url        // why: depends on plugin filesystem constants and plugins_url()
_wp_connectors_init                                // why: constructs the unavailable WP_Connector_Registry and initializes the connector lifecycle
_wp_connectors_register_default_ai_providers       // why: depends on the external WP AI Client registry and provider classes
_wp_connectors_get_api_key_source                  // why: reads a caller-defined option name that cannot be guaranteed in the runtime option store
_wp_connectors_is_ai_api_key_valid                 // why: depends on the external WP AI Client registry and performs provider configuration checks
_wp_connectors_rest_settings_dispatch              // why: depends on REST request/response classes, connector registry state, and option writes
_wp_register_default_connector_settings            // why: depends on connector registry state and the live settings registry
_wp_connectors_pass_default_keys_to_ai_client      // why: depends on the external WP AI Client registry and unresolved connector options
_wp_connectors_get_connector_script_module_data    // why: depends on the AI registry, plugin admin loading, and plugin installation state
*/
