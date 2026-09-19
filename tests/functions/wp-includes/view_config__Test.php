<?php

class view_config__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_get_entity_view_config_hook_name(): void {
		if ( $wp_ver = wp_version_compare( '< 7.1.0' ) ) {
			$this->markTestSkipped( "wp_get_entity_view_config_hook_name() not exists on WP $wp_ver" );
		}

		$this->assertSame( 'get_entity_view_config_post_post', wp_get_entity_view_config_hook_name( 'post', 'post' ) );
	}
}
