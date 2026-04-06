<?php

use PHPUnit\Framework\TestCase;

class ms_blogs__Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['blog_id'] = 1;
		unset( $GLOBALS['current_blog_id'], $GLOBALS['_wp_switched_stack'], $GLOBALS['switched'] );
	}

	public function test__switch_to_blog() {
		$result = switch_to_blog( 5 );

		$this->assertTrue( $result );
		$this->assertSame( 5, $GLOBALS['blog_id'] );
		$this->assertSame( 5, $GLOBALS['current_blog_id'] );
		$this->assertTrue( $GLOBALS['switched'] );
		$this->assertSame( [ 1 ], $GLOBALS['_wp_switched_stack'] );
	}

	public function test__restore_current_blog() {
		switch_to_blog( 5 );
		$result = restore_current_blog();

		$this->assertTrue( $result );
		$this->assertSame( 1, $GLOBALS['blog_id'] );
		$this->assertSame( 1, $GLOBALS['current_blog_id'] );
		$this->assertFalse( $GLOBALS['switched'] );
		$this->assertSame( [], $GLOBALS['_wp_switched_stack'] );
	}

}
