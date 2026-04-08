<?php

class WP_Object_Cache__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$cache = new WP_Object_Cache();

		$cache->__set( 'blog_prefix', 'ok:' );
		$this->assertTrue( $cache->__isset( 'blog_prefix' ) );
		$this->assertSame( 'ok:', $cache->__get( 'blog_prefix' ) );
		$cache->__unset( 'blog_prefix' );
		$this->assertFalse( $cache->__isset( 'blog_prefix' ) );

		$this->assertTrue( $cache->add( 'a', 1, 'g' ) );
		$this->assertFalse( $cache->add( 'a', 2, 'g' ) );
		$this->assertSame( [ 'b' => true, 'c' => true ], $cache->add_multiple( [ 'b' => 2, 'c' => 3 ], 'g' ) );

		$this->assertTrue( $cache->set( 'k', 'v', 'g' ) );
		$this->assertSame( [ 'k2' => true ], $cache->set_multiple( [ 'k2' => 'v2' ], 'g' ) );

		$found = null;
		$this->assertSame( 'v', $cache->get( 'k', 'g' ) );
		$this->assertSame( 'v', $cache->get( 'k', 'g', false, $found ) );
		$this->assertTrue( $found );
		$this->assertSame( [ 'k' => 'v', 'none' => false ], $cache->get_multiple( [ 'k', 'none' ], 'g' ) );

		$this->assertTrue( $cache->replace( 'k', 'vv', 'g' ) );
		$this->assertSame( 'vv', $cache->get( 'k', 'g' ) );
	}

	public function test__numeric_and_cleanup_methods() {
		$cache = new WP_Object_Cache();
		$cache->set( 'num', 5, 'g2' );
		$this->assertSame( 6, $cache->incr( 'num', 1, 'g2' ) );
		$this->assertSame( 4, $cache->decr( 'num', 2, 'g2' ) );

		$this->assertTrue( $cache->delete( 'num', 'g2' ) );
		$cache->set( 'k', 'v', 'g' );
		$this->assertTrue( $cache->delete( 'k', 'g' ) );
		$cache->set_multiple( [ 'x' => 1, 'y' => 2 ], 'g' );
		$this->assertSame( [ 'x' => true, 'y' => true ], $cache->delete_multiple( [ 'x', 'y' ], 'g' ) );

		$cache->set( 'x', '1', 'g' );
		$this->assertTrue( $cache->flush_group( 'g' ) );
		$this->assertFalse( $cache->get( 'x', 'g' ) );

		$cache->add_global_groups( [ 'global' ] );
		$cache->switch_to_blog( 7 );

		$previous = set_error_handler( static fn() => true );
		$cache->reset();
		set_error_handler( $previous );

		$cache->set( 'z', '1', 'gg' );
		$this->assertTrue( $cache->flush() );
		$this->assertFalse( $cache->get( 'z', 'gg' ) );

		ob_start();
		$cache->stats();
		$stats = ob_get_clean();
		$this->assertStringContainsString( 'Cache Hits', $stats );
	}
}
