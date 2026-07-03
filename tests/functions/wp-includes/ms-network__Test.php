<?php

class ms_network__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		global $wp_object_cache;
		$wp_object_cache = new WP_Object_Cache();
	}

	public function test__clean_network_cache() {
		// Array of IDs
		wp_cache_add( 10, (object) [ 'id' => 10 ], 'networks' );
		wp_cache_add( 20, (object) [ 'id' => 20 ], 'networks' );

		clean_network_cache( [ 10, 20 ] );

		$this->assertFalse( wp_cache_get( 10, 'networks' ) );
		$this->assertFalse( wp_cache_get( 20, 'networks' ) );

		// Single ID
		wp_cache_add( 5, (object) [ 'id' => 5 ], 'networks' );

		clean_network_cache( 5 );

		$this->assertFalse( wp_cache_get( 5, 'networks' ) );

		// Suspended cache invalidation
		global $_wp_suspend_cache_invalidation;

		wp_cache_add( 7, (object) [ 'id' => 7 ], 'networks' );

		$_wp_suspend_cache_invalidation = true;
		clean_network_cache( 7 );
		$_wp_suspend_cache_invalidation = false;

		$this->assertNotFalse( wp_cache_get( 7, 'networks' ) );
	}

	public function test__update_network_cache() {
		$net1 = (object) [ 'id' => 1 ];
		$net2 = (object) [ 'id' => 2 ];

		update_network_cache( [ $net1, $net2 ] );

		$this->assertEquals( $net1, wp_cache_get( 1, 'networks' ) );
		$this->assertEquals( $net2, wp_cache_get( 2, 'networks' ) );

		// Does not overwrite existing cache
		$existing = (object) [ 'id' => 3, 'domain' => 'old.test' ];
		wp_cache_add( 3, $existing, 'networks' );

		$new_data = (object) [ 'id' => 3, 'domain' => 'new.test' ];
		update_network_cache( [ $new_data ] );

		$cached = wp_cache_get( 3, 'networks' );
		$this->assertSame( 'old.test', $cached->domain );
	}
}
