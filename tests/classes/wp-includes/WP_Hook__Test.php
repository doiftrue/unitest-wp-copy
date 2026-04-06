<?php

use PHPUnit\Framework\TestCase;

class WP_Hook__Test extends TestCase {

	public function test__filter_action_methods() {
		$hook = new WP_Hook();
		$cb1  = static fn( $v ) => $v . '1';
		$cb2  = static fn( $v ) => $v . '2';
		$hook->add_filter( 'x', $cb1, 10, 1 );
		$hook->add_filter( 'x', $cb2, 20, 1 );

		$out = $hook->apply_filters( 'a', [ 'a' ] );

		$this->assertSame( 'a12', $out );
		$this->assertTrue( $hook->has_filters() );
		$this->assertSame( 10, $hook->has_filter( 'x', $cb1 ) );
		$this->assertTrue( $hook->remove_filter( 'x', $cb1, 10 ) );
		$this->assertFalse( $hook->remove_filter( 'x', $cb1, 10 ) );

		$log = [];
		$act = static function ( $v ) use ( &$log ) {
			$log[] = $v;
		};
		$hook->add_filter( 'x', $act, 30, 1 );
		$hook->do_action( [ 'ping' ] );
		$this->assertContains( 'ping', $log );

		$args = [ 'all' ];
		$hook->do_all_hook( $args );
		$this->assertSame( false, $hook->current_priority() );

		$hook->remove_all_filters( 20 );
		$hook->remove_all_filters();
		$this->assertFalse( $hook->has_filters() );
	}

	public function test__arrayaccess_iterator_and_build_preinitialized_hooks() {
		$hook = new WP_Hook();
		$hook[10] = [ 'k' => [ 'function' => static fn() => null, 'accepted_args' => 0 ] ];
		$this->assertTrue( isset( $hook[10] ) );
		$this->assertIsArray( $hook[10] );
		unset( $hook[10] );
		$this->assertFalse( isset( $hook[10] ) );

		$hook->offsetSet( 15, [ 'k' => [ 'function' => static fn() => null, 'accepted_args' => 0 ] ] );
		$this->assertTrue( $hook->offsetExists( 15 ) );
		$this->assertIsArray( $hook->offsetGet( 15 ) );
		$hook->offsetUnset( 15 );
		$this->assertFalse( $hook->offsetExists( 15 ) );

		$hook->add_filter( 'y', static fn( $v ) => $v, 11, 1 );
		$hook->rewind();
		$this->assertTrue( $hook->valid() );
		$this->assertSame( 11, $hook->key() );
		$this->assertIsArray( $hook->current() );
		$hook->next();

		$normalized = WP_Hook::build_preinitialized_hooks( [
			'z' => [
				10 => [
					[
						'function'      => static fn( $v ) => $v . 'x',
						'accepted_args' => 1,
					],
				],
			],
		] );
		$this->assertArrayHasKey( 'z', $normalized );
		$this->assertInstanceOf( WP_Hook::class, $normalized['z'] );
	}
}

