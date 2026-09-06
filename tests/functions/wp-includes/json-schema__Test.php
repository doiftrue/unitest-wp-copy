<?php

class json_schema__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_get_json_schema_allowed_keywords() {
		if ( $wp_ver = wp_version_compare( '< 7.1.0' ) ) {
			$this->markTestSkipped( "wp_get_json_schema_allowed_keywords() not exists on WP $wp_ver" );
		}

		$keywords = wp_get_json_schema_allowed_keywords( 'rest-api' );

		$this->assertContains( 'type', $keywords );
		$this->assertContains( 'properties', $keywords );
		$this->assertNotContains( '$schema', $keywords );
	}

}
