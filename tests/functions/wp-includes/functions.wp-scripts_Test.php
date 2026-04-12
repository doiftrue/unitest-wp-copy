<?php

class functions_wp_scripts_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_scripts'] = null;
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['pagenow'] = 'index.php';

		do_action( 'init' ); // To mark `init` as fired so style API does not enter _doing_it_wrong() branch.
	}

	public function test__wp_scripts() {
		$this->assertInstanceOf( WP_Scripts::class, wp_scripts() );
	}

	public function test___wp_scripts_maybe_doing_it_wrong() {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__ );
		$this->assertTrue( true );
	}

	public function test__wp_print_scripts() {
		wp_enqueue_script( 'script-print', '/assets/app.js', [], null );

		ob_start();
		$done = wp_print_scripts( [ 'script-print' ] );
		$output = ob_get_clean();

		$this->assertContains( 'script-print', $done );
		$this->assertStringContainsString( '<script', $output );
	}

	public function test__wp_add_inline_script() {
		wp_register_script( 'script-inline', '/assets/inline.js' );
		$this->assertTrue( wp_add_inline_script( 'script-inline', 'console.log("ok");' ) );
		$this->assertContains( 'console.log("ok");', wp_scripts()->get_data( 'script-inline', 'after' ) );
	}

	public function test__wp_register_script() {
		$this->assertTrue( wp_register_script( 'script-reg', '/assets/reg.js', [ 'jquery' ], '1.0.0' ) );
		$this->assertNotFalse( wp_scripts()->query( 'script-reg', 'registered' ) );
	}

	public function test__wp_localize_script() {
		wp_register_script( 'script-l10n', '/assets/l10n.js' );
		$this->assertTrue( wp_localize_script( 'script-l10n', 'AppCfg', [ 'flag' => 'yes' ] ) );

		$data = wp_scripts()->get_data( 'script-l10n', 'data' );
		$this->assertStringContainsString( 'var AppCfg', $data );
	}

	public function test__wp_set_script_translations() {
		wp_register_script( 'script-tr', '/assets/tr.js' );

		$this->assertTrue( wp_set_script_translations( 'script-tr', 'my-domain', '/tmp/lang' ) );
		$this->assertContains( 'wp-i18n', wp_scripts()->registered['script-tr']->deps );
		$this->assertSame( 'my-domain', wp_scripts()->registered['script-tr']->textdomain );
	}

	public function test__not_independent_wp_set_script_translations__translation_loading_runtime() {
		wp_register_script( 'wp-i18n', false );
		wp_register_script( 'script-tr-runtime', '/assets/tr-runtime.js' );
		wp_set_script_translations( 'script-tr-runtime', 'my-domain', '/tmp/lang' );
		wp_enqueue_script( 'script-tr-runtime' );

		$this->expectException( Error::class );
		wp_print_scripts( [ 'script-tr-runtime' ] );
	}

	public function test__wp_deregister_script() {
		wp_register_script( 'script-dereg', '/assets/dereg.js' );
		wp_deregister_script( 'script-dereg' );
		$this->assertFalse( wp_scripts()->query( 'script-dereg', 'registered' ) );
	}

	public function test__wp_enqueue_script() {
		wp_enqueue_script( 'script-enq', '/assets/enq.js' );
		$this->assertTrue( wp_script_is( 'script-enq', 'enqueued' ) );
	}

	public function test__wp_dequeue_script() {
		wp_enqueue_script( 'script-deq', '/assets/deq.js' );
		wp_dequeue_script( 'script-deq' );
		$this->assertFalse( wp_script_is( 'script-deq', 'enqueued' ) );
	}

	public function test__wp_script_is() {
		wp_register_script( 'script-check', '/assets/check.js' );
		$this->assertTrue( wp_script_is( 'script-check', 'registered' ) );
		$this->assertFalse( wp_script_is( 'script-check', 'enqueued' ) );
	}

	public function test__wp_script_add_data() {
		wp_register_script( 'script-data', '/assets/data.js' );
		$this->assertTrue( wp_script_add_data( 'script-data', 'strategy', 'defer' ) );
		$this->assertSame( 'defer', wp_scripts()->get_data( 'script-data', 'strategy' ) );
	}

}
