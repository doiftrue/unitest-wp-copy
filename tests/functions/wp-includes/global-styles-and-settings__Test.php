<?php

class global_styles_and_settings__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_get_block_name_from_theme_json_path() {
		if( $wp_ver = wp_version_compare( '< 6.3.0' ) ){
			$this->markTestSkipped( "wp_get_block_name_from_theme_json_path() not exists on WP $wp_ver" );
		}

		$this->assertSame( 'plugin/card', wp_get_block_name_from_theme_json_path( [ 'styles', 'blocks', 'plugin/card' ] ) );
		$this->assertSame( 'core/image', wp_get_block_name_from_theme_json_path( [ 'settings', 'core/image', 'color' ] ) );
		$this->assertSame( '', wp_get_block_name_from_theme_json_path( [ 'styles', 'elements', 'link' ] ) );
	}
}
