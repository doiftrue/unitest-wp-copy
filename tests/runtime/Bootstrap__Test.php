<?php

use Unitest_WP_Copy\Bootstrap;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Bootstrap__Test extends Project_TestCase {

	public function test__init(): void {
		// NOTE: Bootstrap::init() already run on test init
		$this->assertTrue( Bootstrap::init() instanceof Bootstrap );
	}

	public function test__detect_wp_line(): void {
		$wp_line = Closure::bind( fn() => $this->detect_wp_line(), new Bootstrap(), Bootstrap::class )();

		$this->assertMatchesRegularExpression( '/^\d+\.\d+$/', $wp_line );
	}

	public function test__resolve_wp_line_extra_file__uses_wp_line_extra(): void {
		[ $bootstrap, $base_dir, $over_dir ] = $this->make_bootstrap_with_test_dirs();
		$base_file = "$base_dir/init-parts/wp-includes/kses.php";
		$over_file = "$over_dir/init-parts/wp-includes/kses.php";

		file_put_contents( $base_file, "<?php\n" );
		file_put_contents( $over_file, "<?php\n" );

		$resolved = Closure::bind( fn() => $this->resolve_wp_line_extra_file( $base_file ), $bootstrap, Bootstrap::class )();

		$this->assertSame( $over_file, $resolved );
	}

	public function test__resolve_wp_line_extra_file__falls_back_to_base(): void {
		[ $bootstrap, $base_dir ] = $this->make_bootstrap_with_test_dirs();
		$base_file = "$base_dir/init-parts/wp-includes/plugin.php";

		file_put_contents( $base_file, "<?php\n" );

		$resolved = Closure::bind( fn() => $this->resolve_wp_line_extra_file( $base_file ), $bootstrap, Bootstrap::class )();

		$this->assertSame( $base_file, $resolved );
	}

	private function make_bootstrap_with_test_dirs(): array {
		$tmp_dir = $this->make_temp_dir( 'bootstrap-test' );
		$base_dir = "$tmp_dir/base";
		$over_dir = "$tmp_dir/wp-line-extra/9.9";

		mkdir( "$base_dir/init-parts/wp-includes", 0777, true );
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
