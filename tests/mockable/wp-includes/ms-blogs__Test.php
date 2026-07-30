<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class ms_blogs__mockable__Test extends \PHPUnit\Framework\TestCase {

	private ?array $switched_stack;

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$this->switched_stack = $GLOBALS['_wp_switched_stack'] ?? null;
		$GLOBALS['_wp_switched_stack'] = [];
	}

	protected function tearDown(): void {
		if ( null === $this->switched_stack ) {
			unset( $GLOBALS['_wp_switched_stack'] );
		} else {
			$GLOBALS['_wp_switched_stack'] = $this->switched_stack;
		}
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__ms_is_switched(): void {
		$this->assertFalse( ms_is_switched() );

		$GLOBALS['_wp_switched_stack'] = [ 1 ];
		$this->assertTrue( ms_is_switched() );
	}

	public function test__ms_is_switched__mockable_handler(): void {
		\WP_Mock::userFunction( 'ms_is_switched', [ 'return' => true ] );
		$this->assertTrue( ms_is_switched() );
	}
}
