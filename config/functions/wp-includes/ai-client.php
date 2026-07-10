<?php

return [
	'wp_supports_ai' => '7.0.0 mockable',
];

/*
Not suitable in isolated PHPUnit env:

wp_ai_client_prompt // why: depends on WP_AI_Client_Prompt_Builder and the external WP AI Client provider registry.
*/
