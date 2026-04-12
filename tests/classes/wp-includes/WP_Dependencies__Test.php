<?php

class WP_Dependencies__Test extends \PHPUnit\Framework\TestCase {

	public function test__register_enqueue_and_process() {
		$deps = new WP_Dependencies();

		$this->assertTrue( $deps->add( 'dep-a', '/assets/a.js', [] ) );
		$this->assertTrue( $deps->add( 'dep-b', '/assets/b.js', [ 'dep-a' ] ) );
		$this->assertTrue( $deps->add_data( 'dep-b', 'group', 1 ) );

		$deps->enqueue( 'dep-b' );

		$this->assertTrue( $deps->query( 'dep-b', 'enqueued' ) );
		$this->assertTrue( $deps->all_deps( [ 'dep-b' ] ) );

		$done = $deps->do_items();
		$this->assertContains( 'dep-a', $done );
		$this->assertContains( 'dep-b', $done );
	}

	public function test__do_item() {
		$deps = new WP_Dependencies();

		$deps->add( 'dep-item', '/assets/item.js', [] );

		$this->assertTrue( $deps->do_item( 'dep-item' ) );
		$this->assertFalse( $deps->do_item( 'dep-missing' ) );
	}

	public function test__all_deps() {
		$deps = new WP_Dependencies();

		$deps->add( 'dep-a', '/assets/a.js', [] );
		$deps->add( 'dep-b', '/assets/b.js', [ 'dep-a' ] );

		$this->assertTrue( $deps->all_deps( [ 'dep-b' ] ) );
		$this->assertContains( 'dep-a', $deps->to_do );
		$this->assertContains( 'dep-b', $deps->to_do );
	}

	public function test__remove() {
		$deps = new WP_Dependencies();

		$deps->add( 'dep-remove', '/assets/remove.js', [] );
		$this->assertNotFalse( $deps->query( 'dep-remove', 'registered' ) );

		$deps->remove( 'dep-remove' );
		$this->assertFalse( $deps->query( 'dep-remove', 'registered' ) );
	}

	public function test__dequeue() {
		$deps = new WP_Dependencies();

		$deps->add( 'dep-dequeue', '/assets/dequeue.js', [] );
		$deps->enqueue( 'dep-dequeue?ver=1' );
		$this->assertTrue( $deps->query( 'dep-dequeue', 'enqueued' ) );
		$this->assertArrayHasKey( 'dep-dequeue', $deps->args );

		$deps->dequeue( 'dep-dequeue' );
		$this->assertFalse( $deps->query( 'dep-dequeue', 'enqueued' ) );
		$this->assertArrayNotHasKey( 'dep-dequeue', $deps->args );
	}

}
