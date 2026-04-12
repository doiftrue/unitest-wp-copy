<?php

class functions_wp_styles_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_styles'] = null;
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];

		do_action( 'init' ); // To mark `init` as fired so style API does not enter _doing_it_wrong() branch.
	}

	public function test__wp_styles() {
		$this->assertInstanceOf( WP_Styles::class, wp_styles() );
	}

	public function test__wp_print_styles() {
		wp_enqueue_style( 'style-print', '/assets/app.css', [], null );

		ob_start();
		$done = wp_print_styles( [ 'style-print' ] );
		$output = ob_get_clean();

		$this->assertContains( 'style-print', $done );
		$this->assertStringContainsString( '<link', $output );
	}

	public function test__wp_add_inline_style() {
		wp_register_style( 'style-inline', '/assets/inline.css' );
		$this->assertTrue( wp_add_inline_style( 'style-inline', 'body{color:red;}' ) );
		$this->assertSame( [ 'body{color:red;}' ], wp_styles()->get_data( 'style-inline', 'after' ) );
	}

	public function test__wp_register_style() {
		$this->assertTrue( wp_register_style( 'style-reg', '/assets/reg.css', [ 'dashicons' ], '1.0.0', 'print' ) );
		$this->assertNotFalse( wp_styles()->query( 'style-reg', 'registered' ) );
	}

	public function test__wp_deregister_style() {
		wp_register_style( 'style-dereg', '/assets/dereg.css' );
		wp_deregister_style( 'style-dereg' );
		$this->assertFalse( wp_styles()->query( 'style-dereg', 'registered' ) );
	}

	public function test__wp_enqueue_style() {
		wp_enqueue_style( 'style-enq', '/assets/enq.css' );
		$this->assertTrue( wp_style_is( 'style-enq', 'enqueued' ) );
	}

	public function test__wp_dequeue_style() {
		wp_enqueue_style( 'style-deq', '/assets/deq.css' );
		wp_dequeue_style( 'style-deq' );
		$this->assertFalse( wp_style_is( 'style-deq', 'enqueued' ) );
	}

	public function test__wp_style_is() {
		wp_register_style( 'style-check', '/assets/check.css' );
		$this->assertTrue( wp_style_is( 'style-check', 'registered' ) );
		$this->assertFalse( wp_style_is( 'style-check', 'enqueued' ) );
	}

	public function test__wp_style_add_data() {
		wp_register_style( 'style-data', '/assets/data.css' );
		$this->assertTrue( wp_style_add_data( 'style-data', 'rtl', 'replace' ) );
		$this->assertSame( 'replace', wp_styles()->get_data( 'style-data', 'rtl' ) );
	}

}
