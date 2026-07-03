<?php

class ms_site__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		global $wp_object_cache;
		$wp_object_cache = new WP_Object_Cache();
	}

	public function test__wp_cache_set_sites_last_changed() {
		// Ensure no prior value
		$this->assertFalse( wp_cache_get( 'last_changed', 'sites' ) );

		wp_cache_set_sites_last_changed();

		$last_changed = wp_cache_get( 'last_changed', 'sites' );
		$this->assertNotFalse( $last_changed );
		$this->assertIsString( $last_changed );
	}
}
