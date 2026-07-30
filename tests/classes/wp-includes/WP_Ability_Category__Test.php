<?php

class WP_Ability_Category__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "WP_Ability_Category not exists on WP $wp_ver" );
		}

		$category = new WP_Ability_Category( 'content', [
			'label'       => 'Content',
			'description' => 'Content abilities.',
			'meta'        => [ 'group' => 'editor' ],
		] );

		$this->assertSame( 'content', $category->get_slug() );
		$this->assertSame( 'Content', $category->get_label() );
		$this->assertSame( [ 'group' => 'editor' ], $category->get_meta() );
	}
}
