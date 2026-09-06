<?php

// ------------------auto-generated---------------------

// wp-includes/json-schema.php (WP 7.1)
if( ! function_exists( 'wp_get_json_schema_allowed_keywords' ) ) :
	function wp_get_json_schema_allowed_keywords( string $schema_profile = 'rest-api' ): array {
		$rest_keywords = rest_get_allowed_schema_keywords();
	
		$keywords_by_profile = array(
			'rest-api' => $rest_keywords,
			'draft-04' => array_merge(
				array(
					'$schema',
					'id',
					'$ref',
				),
				$rest_keywords,
				array(
					'required',
					'allOf',
					'not',
					'definitions',
					'dependencies',
					'additionalItems',
				)
			),
		);
	
		$allowed_keywords = $keywords_by_profile[ $schema_profile ] ?? $rest_keywords;
	
		/**
		 * Filters the JSON Schema keywords allowed for a given schema profile.
		 *
		 * Use this to decide which keywords may be exposed to clients for a profile.
		 * It does not make WordPress validate or sanitize values against the keyword.
		 *
		 * @since 7.1.0
		 *
		 * @param string[] $allowed_keywords Allowed JSON Schema keywords.
		 * @param string   $schema_profile   The schema profile the keywords are for.
		 */
		return apply_filters( 'wp_json_schema_allowed_keywords', $allowed_keywords, $schema_profile );
	}
endif;

