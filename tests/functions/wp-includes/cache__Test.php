<?php

class cache__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		// Reset the object cache before each test.
		global $wp_object_cache;
		$wp_object_cache = new WP_Object_Cache();
	}

	public function test__wp_cache_init() {
		global $wp_object_cache;
		$wp_object_cache = null;

		wp_cache_init();

		$this->assertInstanceOf( WP_Object_Cache::class, $wp_object_cache );
	}

	public function test__wp_cache_add() {
		$this->assertTrue( wp_cache_add( 'key1', 'value1' ) );
		$this->assertFalse( wp_cache_add( 'key1', 'value2' ) );
		$this->assertSame( 'value1', wp_cache_get( 'key1' ) );
	}

	public function test__wp_cache_add_multiple() {
		$result = wp_cache_add_multiple( [ 'k1' => 'v1', 'k2' => 'v2' ] );
		$this->assertTrue( $result['k1'] );
		$this->assertTrue( $result['k2'] );
		$this->assertSame( 'v1', wp_cache_get( 'k1' ) );
		$this->assertSame( 'v2', wp_cache_get( 'k2' ) );
	}

	public function test__wp_cache_replace() {
		$this->assertFalse( wp_cache_replace( 'nokey', 'val' ) );

		wp_cache_set( 'rkey', 'original' );
		$this->assertTrue( wp_cache_replace( 'rkey', 'replaced' ) );
		$this->assertSame( 'replaced', wp_cache_get( 'rkey' ) );
	}

	public function test__wp_cache_set() {
		$this->assertTrue( wp_cache_set( 'skey', 'sval', 'grp' ) );
		$this->assertSame( 'sval', wp_cache_get( 'skey', 'grp' ) );
	}

	public function test__wp_cache_set_multiple() {
		$result = wp_cache_set_multiple( [ 'x' => 10, 'y' => 20 ] );
		$this->assertTrue( $result['x'] );
		$this->assertTrue( $result['y'] );
		$this->assertSame( 10, wp_cache_get( 'x' ) );
		$this->assertSame( 20, wp_cache_get( 'y' ) );
	}

	public function test__wp_cache_get() {
		wp_cache_set( 'gkey', 'gval' );
		$this->assertSame( 'gval', wp_cache_get( 'gkey' ) );
		$this->assertFalse( wp_cache_get( 'nonexistent' ) );
	}

	public function test__wp_cache_delete() {
		wp_cache_set( 'del', 'val' );
		$this->assertTrue( wp_cache_delete( 'del' ) );
		$this->assertFalse( wp_cache_get( 'del' ) );
	}

	public function test__wp_cache_delete_multiple() {
		wp_cache_set( 'd1', 1 );
		wp_cache_set( 'd2', 2 );
		$result = wp_cache_delete_multiple( [ 'd1', 'd2' ] );
		$this->assertTrue( $result['d1'] );
		$this->assertTrue( $result['d2'] );
		$this->assertFalse( wp_cache_get( 'd1' ) );
	}

	public function test__wp_cache_incr() {
		wp_cache_set( 'counter', 10 );
		$this->assertSame( 11, wp_cache_incr( 'counter' ) );
		$this->assertSame( 14, wp_cache_incr( 'counter', 3 ) );
	}

	public function test__wp_cache_decr() {
		wp_cache_set( 'counter', 10 );
		$this->assertSame( 8, wp_cache_decr( 'counter', 2 ) );
		$this->assertSame( 7, wp_cache_decr( 'counter' ) );
	}

	public function test__wp_cache_flush() {
		wp_cache_set( 'a', 1 );
		wp_cache_set( 'b', 2 );
		$this->assertTrue( wp_cache_flush() );
		$this->assertFalse( wp_cache_get( 'a' ) );
		$this->assertFalse( wp_cache_get( 'b' ) );
	}

	public function test__wp_cache_flush_runtime() {
		wp_cache_set( 'rt', 'data' );
		$this->assertTrue( wp_cache_flush_runtime() );
		$this->assertFalse( wp_cache_get( 'rt' ) );
	}

	public function test__wp_cache_flush_group() {
		wp_cache_set( 'fg1', 'val1', 'mygroup' );
		wp_cache_set( 'fg2', 'val2', 'mygroup' );
		wp_cache_set( 'other', 'val3', 'othergroup' );

		$this->assertTrue( wp_cache_flush_group( 'mygroup' ) );
		$this->assertFalse( wp_cache_get( 'fg1', 'mygroup' ) );
		$this->assertFalse( wp_cache_get( 'fg2', 'mygroup' ) );
		$this->assertSame( 'val3', wp_cache_get( 'other', 'othergroup' ) );
	}

	public function test__wp_cache_supports() {
		$this->assertTrue( wp_cache_supports( 'add_multiple' ) );
		$this->assertTrue( wp_cache_supports( 'set_multiple' ) );
		$this->assertTrue( wp_cache_supports( 'get_multiple' ) );
		$this->assertTrue( wp_cache_supports( 'delete_multiple' ) );
		$this->assertTrue( wp_cache_supports( 'flush_runtime' ) );
		$this->assertTrue( wp_cache_supports( 'flush_group' ) );
		$this->assertFalse( wp_cache_supports( 'nonexistent_feature' ) );
	}

	public function test__wp_cache_close() {
		$this->assertTrue( wp_cache_close() );
	}

	public function test__wp_cache_add_global_groups() {
		global $wp_object_cache;

		wp_cache_add_global_groups( 'global_grp' );
		$this->assertArrayHasKey( 'global_grp', $wp_object_cache->global_groups );

		wp_cache_add_global_groups( [ 'grp_a', 'grp_b' ] );
		$this->assertArrayHasKey( 'grp_a', $wp_object_cache->global_groups );
		$this->assertArrayHasKey( 'grp_b', $wp_object_cache->global_groups );
	}

	public function test__wp_cache_add_non_persistent_groups() {
		// This is a no-op in default cache — just verify it doesn't fatal.
		wp_cache_add_non_persistent_groups( 'volatile' );
		wp_cache_add_non_persistent_groups( [ 'a', 'b' ] );
		$this->assertTrue( true );
	}

	public function test__wp_cache_switch_to_blog() {
		// switch_to_blog only affects blog_prefix when is_multisite() is true.
		// In non-multisite env, just verify no fatal occurs.
		wp_cache_switch_to_blog( 5 );

		global $wp_object_cache;
		// blog_prefix is empty string in non-multisite, '5:' in multisite.
		$this->assertIsString( $wp_object_cache->blog_prefix );
	}

	public function test__wp_cache_get_salted() {
		if ( $wp_ver = wp_version_compare( '< 6.9.0' ) ) {
			$this->markTestSkipped( "wp_cache_get_salted() not exists on WP $wp_ver" );
		}

		wp_cache_set_salted( 'skey', 'sdata', 'grp', 'salt1' );
		$this->assertSame( 'sdata', wp_cache_get_salted( 'skey', 'grp', 'salt1' ) );
		$this->assertFalse( wp_cache_get_salted( 'skey', 'grp', 'wrong_salt' ) );
	}

	public function test__wp_cache_set_salted() {
		if ( $wp_ver = wp_version_compare( '< 6.9.0' ) ) {
			$this->markTestSkipped( "wp_cache_set_salted() not exists on WP $wp_ver" );
		}

		$this->assertTrue( wp_cache_set_salted( 'k', 'data', 'g', 'ts' ) );
		$this->assertSame( 'data', wp_cache_get_salted( 'k', 'g', 'ts' ) );
	}

	public function test__wp_cache_get_multiple_salted() {
		if ( $wp_ver = wp_version_compare( '< 6.9.0' ) ) {
			$this->markTestSkipped( "wp_cache_get_multiple_salted() not exists on WP $wp_ver" );
		}

		wp_cache_set_salted( 'k1', 'v1', 'grp', 'ts1' );
		wp_cache_set_salted( 'k2', 'v2', 'grp', 'ts1' );

		$result = wp_cache_get_multiple_salted( [ 'k1', 'k2' ], 'grp', 'ts1' );
		$this->assertSame( 'v1', $result['k1'] );
		$this->assertSame( 'v2', $result['k2'] );

		$result = wp_cache_get_multiple_salted( [ 'k1', 'k2' ], 'grp', 'wrong' );
		$this->assertFalse( $result['k1'] );
		$this->assertFalse( $result['k2'] );
	}

	public function test__wp_cache_set_multiple_salted() {
		if ( $wp_ver = wp_version_compare( '< 6.9.0' ) ) {
			$this->markTestSkipped( "wp_cache_set_multiple_salted() not exists on WP $wp_ver" );
		}

		$data = [ 'a' => 'alpha', 'b' => 'beta' ];
		$result = wp_cache_set_multiple_salted( $data, 'grp', 'salt' );
		$this->assertTrue( $result['a'] );
		$this->assertTrue( $result['b'] );

		$cached = wp_cache_get_multiple_salted( [ 'a', 'b' ], 'grp', 'salt' );
		$this->assertSame( 'alpha', $cached['a'] );
		$this->assertSame( 'beta', $cached['b'] );
	}
}
