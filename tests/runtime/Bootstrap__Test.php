<?php

use Unitest_WP_Copy\Bootstrap;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Bootstrap__Test extends Project_TestCase {

	public function test__init(): void {
		// NOTE: Bootstrap::init() already run on test init
		$this->assertTrue( Bootstrap::init() instanceof Bootstrap );
		$this->assertSame( '2.0', REST_API_VERSION );
		$this->assertSame( '1', $GLOBALS['stub_wp_options']->blog_public );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test__init__loads_rest_dispatch_filters(): void {
		$this->assertSame( 10, has_filter( 'rest_pre_dispatch', 'rest_handle_options_request' ) );
		$this->assertFalse( has_filter( 'rest_post_dispatch', 'rest_send_allow_header' ) );
		$this->assertFalse( has_filter( 'rest_post_dispatch', 'rest_filter_response_fields' ) );
	}

	public function test__detect_wp_line(): void {
		$wp_line = Closure::bind( fn() => $this->detect_wp_line(), new Bootstrap(), Bootstrap::class )();

		$this->assertMatchesRegularExpression( '/^\d+\.\d+$/', $wp_line );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test__load_init_parts__loads_base_and_wp_line_files(): void {
		[ $bootstrap, $base_dir, $over_dir ] = $this->make_bootstrap_with_test_dirs();

		file_put_contents( "$base_dir/init-parts-modified/wp-includes/plugin.php", "<?php\n\$GLOBALS['bootstrap_init_parts_test'][] = 'base-plugin';\n" );
		file_put_contents( "$over_dir/init-parts/wp-includes/kses.php", "<?php\n\$GLOBALS['bootstrap_init_parts_test'][] = 'line-kses';\n" );

		Closure::bind( fn() => $this->load_init_parts(), $bootstrap, Bootstrap::class )();

		$this->assertSame( [ 'base-plugin', 'line-kses' ], $GLOBALS['bootstrap_init_parts_test'] );
	}

	private function make_bootstrap_with_test_dirs(): array {
		$tmp_dir = $this->make_temp_dir( 'bootstrap-test' );
		$base_dir = "$tmp_dir/base";
		$over_dir = "$tmp_dir/wp-line-extra/9.9";

		mkdir( "$base_dir/init-parts-modified/wp-includes", 0777, true );
		mkdir( "$over_dir/init-parts/wp-includes", 0777, true );

		$bootstrap = new Bootstrap();

		Closure::bind(
			function() use ( $base_dir, $over_dir ) {
				$this->base_dir = $base_dir;
				$this->line_extra_dir = $over_dir;
			},
			$bootstrap,
			Bootstrap::class
		)();

		return [ $bootstrap, $base_dir, $over_dir ];
	}

}
