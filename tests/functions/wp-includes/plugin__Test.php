<?php

use PHPUnit\Framework\TestCase;

class plugin__Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_plugin_paths']   = [];
	}

	public function test__add_filter() {
		add_filter( 'add_filter_h', '__return_true' );
		$this->assertNotFalse( has_filter( 'add_filter_h', '__return_true' ) );
	}

	public function test__apply_filters() {
		add_filter( 'apply_filters_h', static function ( $v ) { return $v . '!'; } );
		$this->assertSame( 'ok!', apply_filters( 'apply_filters_h', 'ok' ) );
	}

	public function test__apply_filters_ref_array() {
		add_filter(
			'apply_filters_ref_array_h',
			static function ( $v, $suffix ) {
				return $v . $suffix;
			},
			10,
			2
		);
		$this->assertSame( 'ab', apply_filters_ref_array( 'apply_filters_ref_array_h', [ 'a', 'b' ] ) );
	}

	public function test__has_filter() {
		add_filter( 'has_filter_h', '__return_true' );
		$this->assertNotFalse( has_filter( 'has_filter_h', '__return_true' ) );
	}

	public function test__remove_filter() {
		add_filter( 'remove_filter_h', '__return_true' );
		$this->assertTrue( remove_filter( 'remove_filter_h', '__return_true' ) );
		$this->assertFalse( has_filter( 'remove_filter_h', '__return_true' ) );
	}

	public function test__remove_all_filters() {
		add_filter( 'remove_all_filters_h', '__return_true' );
		add_filter( 'remove_all_filters_h', '__return_false', 11 );
		$this->assertTrue( remove_all_filters( 'remove_all_filters_h' ) );
		$this->assertFalse( has_filter( 'remove_all_filters_h' ) );
	}

	public function test__current_filter() {
		add_filter( 'current_filter_h', static function ( $v ) { return current_filter(); } );
		$this->assertSame( 'current_filter_h', apply_filters( 'current_filter_h', 'x' ) );
	}

	public function test__doing_filter() {
		add_filter( 'doing_filter_h', static function ( $v ) { return doing_filter( 'doing_filter_h' ); } );
		$this->assertTrue( apply_filters( 'doing_filter_h', 'x' ) );
	}

	public function test__did_filter() {
		apply_filters( 'did_filter_h', 'x' );
		$this->assertSame( 1, did_filter( 'did_filter_h' ) );
	}

	public function test__add_action() {
		add_action( 'add_action_h', '__return_null' );
		$this->assertNotFalse( has_action( 'add_action_h', '__return_null' ) );
	}

	public function test__do_action() {
		$GLOBALS['do_action_hits'] = 0;
		add_action( 'do_action_h', static function () { $GLOBALS['do_action_hits']++; } );
		do_action( 'do_action_h' );
		$this->assertSame( 1, $GLOBALS['do_action_hits'] );
	}

	public function test__do_action_ref_array() {
		$GLOBALS['do_action_ref_array_arg'] = '';
		add_action( 'do_action_ref_array_h', static function ( $v ) { $GLOBALS['do_action_ref_array_arg'] = $v; } );
		do_action_ref_array( 'do_action_ref_array_h', [ 'ok' ] );
		$this->assertSame( 'ok', $GLOBALS['do_action_ref_array_arg'] );
	}

	public function test__has_action() {
		add_action( 'has_action_h', '__return_null' );
		$this->assertNotFalse( has_action( 'has_action_h', '__return_null' ) );
	}

	public function test__remove_action() {
		add_action( 'remove_action_h', '__return_null' );
		$this->assertTrue( remove_action( 'remove_action_h', '__return_null' ) );
		$this->assertFalse( has_action( 'remove_action_h', '__return_null' ) );
	}

	public function test__remove_all_actions() {
		add_action( 'remove_all_actions_h', '__return_null' );
		$this->assertTrue( remove_all_actions( 'remove_all_actions_h' ) );
		$this->assertFalse( has_action( 'remove_all_actions_h' ) );
	}

	public function test__current_action() {
		$GLOBALS['current_action_name'] = '';
		add_action( 'current_action_h', static function () { $GLOBALS['current_action_name'] = current_action(); } );
		do_action( 'current_action_h' );
		$this->assertSame( 'current_action_h', $GLOBALS['current_action_name'] );
	}

	public function test__doing_action() {
		$GLOBALS['doing_action_state'] = false;
		add_action( 'doing_action_h', static function () { $GLOBALS['doing_action_state'] = doing_action( 'doing_action_h' ); } );
		do_action( 'doing_action_h' );
		$this->assertTrue( $GLOBALS['doing_action_state'] );
	}

	public function test__did_action() {
		do_action( 'did_action_h' );
		$this->assertSame( 1, did_action( 'did_action_h' ) );
	}

	public function test__plugin_basename() {
		$this->assertSame(
			'my-plugin/main.php',
			plugin_basename( '/path/to/wp/wp-content/plugins/my-plugin/main.php' )
		);
	}

	public function test__wp_register_plugin_realpath() {
		$tmp = tempnam( sys_get_temp_dir(), 'plug' );
		$this->assertTrue( wp_register_plugin_realpath( $tmp ) );
		unlink( $tmp );
	}

	public function test___wp_filter_build_unique_id() {
		$this->assertSame( 'strlen', _wp_filter_build_unique_id( 'h', 'strlen', 10 ) );

		$obj = new stdClass();
		$id  = _wp_filter_build_unique_id( 'h', [ $obj, 'm' ], 10 );
		$this->assertStringContainsString( 'm', $id );
	}

}
