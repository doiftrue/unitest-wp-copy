<?php

use Parser\Config;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Config__Test extends Project_TestCase {

	use Parser_Config__Test_Utils;

	public function test__parse_version_line__extracts_major_minor() {
		$result = $this->call_private_method( 'parse_version_line', [ '6.8.1-alpha' ] );
		$this->assertSame( '6.8', $result );
	}

	public function test__apply_moves_config__moves_for_older_version() {
		$base_config = [
			'wp-includes/load.php'      => [
				'absint' => '2.5.0',
			],
			'wp-includes/functions.php' => [
				'path_is_absolute' => '2.5.0',
			],
		];

		$mv_config = [
			'absint' => [
				'moved_in' => '6.7',
				'from'    => 'wp-includes/functions.php',
				'to'      => 'wp-includes/load.php',
			],
		];

		$result = $this->call_private_method( 'apply_moves_config', [ $base_config, $mv_config, '6.6' ] );

		// should be moved
		$this->assertArrayNotHasKey( 'absint', $result['wp-includes/load.php'] );
		$this->assertSame( '2.5.0', $result['wp-includes/functions.php']['absint'] );
		$this->assertSame( '2.5.0', $result['wp-includes/functions.php']['path_is_absolute'] );
	}

	public function test__apply_moves_config__moves_for_newer_version() {
		$base_config = [
			'wp-includes/functions.php' => [
				'path_is_absolute' => '2.5.0',
			],
			'wp-includes/load.php'      => [
				'absint' => '2.5.0',
			],
		];

		$mv_config = [
			'absint' => [
				'moved_in' => '6.7',
				'from'    => 'wp-includes/functions.php',
				'to'      => 'wp-includes/load.php',
			],
		];

		$result = $this->call_private_method( 'apply_moves_config', [ $base_config, $mv_config, '6.8' ] );

		// should stay as it was
		$this->assertArrayNotHasKey( 'absint', $result['wp-includes/functions.php'] );
		$this->assertSame( '2.5.0', $result['wp-includes/load.php']['absint'] );
		$this->assertSame( '2.5.0', $result['wp-includes/functions.php']['path_is_absolute'] );
	}

	public function test__load_php_config_file__returns_empty_for_missing_optional_file() {
		$tmp_dir = $this->make_temp_dir();
		$result  = $this->call_private_method( 'load_php_config_file', [ "$tmp_dir/missing.php", false ] );

		$this->assertSame( [], $result );
	}

	public function test__load_php_config_file__loads_existing_file() {
		$tmp_dir = $this->make_temp_dir();
		$file    = "$tmp_dir/config.php";

		$this->write_config_data_to_file( $file, [ 'k' => 'v' ] );

		$result = $this->call_private_method( 'load_php_config_file', [ $file, true ] );

		$this->assertSame( [ 'k' => 'v' ], $result );
	}

	public function test__load_nested_config_files__returns_empty_for_missing_dir() {
		$tmp_dir = $this->make_temp_dir();
		$result  = $this->call_private_method( 'load_nested_config_files', [ "$tmp_dir/missing" ] );

		$this->assertSame( [], $result );
	}

	public function test__load_nested_config_files__loads_php_files_recursively_and_sorts_them() {
		$tmp_dir = $this->make_temp_dir();
		$base    = "$tmp_dir/functions";

		$this->write_config_data_to_file( "$base/wp-includes/load.php", [ 'a' => '1' ] );
		$this->write_config_data_to_file( "$base/wp-admin/includes/screen.php", [ 'b' => '2' ] );
		file_put_contents( "$base/wp-admin/includes/ignored.txt", 'skip' );

		$result = $this->call_private_method( 'load_nested_config_files', [ $base ] );

		$this->assertSame(
			[ 'wp-admin/includes/screen.php', 'wp-includes/load.php' ],
			array_keys( $result )
		);
		$this->assertSame( [ 'a' => '1' ], $result['wp-includes/load.php'] );
		$this->assertSame( [ 'b' => '2' ], $result['wp-admin/includes/screen.php'] );
	}

	public function test__merge_flat_configs__overrides_and_deletes_items() {
		$base_config = [
			'a.php' => [ 'class' => 'A' ],
			'b.php' => [ 'class' => 'B' ],
		];
		$ver_config = [
			'a.php' => false,
			'c.php' => [ 'class' => 'C' ],
		];

		$result = $this->call_private_method( 'merge_flat_configs', [ $base_config, $ver_config ] );

		$this->assertSame(
			[
				'b.php' => [ 'class' => 'B' ],
				'c.php' => [ 'class' => 'C' ],
			],
			$result
		);
	}

	public function test__merge_nested_configs__overrides_and_deletes_symbols() {
		$base_config = [
			'file-a.php' => [
				'foo' => '1.0.0',
				'bar' => '1.0.0',
			],
		];
		$ver_config = [
			'file-a.php' => [
				'bar' => false,
				'baz' => '2.0.0',
			],
			'file-b.php' => [
				'new' => '3.0.0',
			],
		];

		$result = $this->call_private_method( 'merge_nested_configs', [ $base_config, $ver_config ] );

		$this->assertSame(
			[
				'file-a.php' => [
					'foo' => '1.0.0',
					'baz' => '2.0.0',
				],
				'file-b.php' => [
					'new' => '3.0.0',
				],
			],
			$result
		);
	}

	public function test__build_classes_config__merges_base_and_version_config() {
		$tmp_dir    = $this->make_temp_dir();
		$config_dir = "$tmp_dir/config";
		$line_dir   = "$config_dir/6.6";

		$this->write_config_data_to_file( "$config_dir/classes.php", [
			'wp-includes/a.php' => [
				'A' => '1.0.0',
				'B' => '1.0.0',
			],
		] );
		$this->write_config_data_to_file( "$line_dir/classes.php", [
			'wp-includes/a.php' => [
				'B' => false,
				'C' => '2.0.0',
			],
		] );

		$config = $this->make_config( [
			'config_dir'      => $config_dir,
			'line_config_dir' => $line_dir,
		] );
		$result = $this->call_private_method( 'build_classes_config', [], $config );

		$this->assertSame(
			[
				'wp-includes/a.php' => [
					'A' => '1.0.0',
					'C' => '2.0.0',
				],
			],
			$result
		);
	}

	public function test__build_static_methods_config__merges_base_and_version_config() {
		$tmp_dir    = $this->make_temp_dir();
		$config_dir = "$tmp_dir/config";
		$line_dir   = "$config_dir/6.6";

		$this->write_config_data_to_file(
			"$config_dir/static-methods.php",
			[
				'wp-includes/class-a.php' => [
					'class'   => 'ClassA',
					'methods' => [ 'm1' => '' ],
				],
				'wp-includes/class-b.php' => [
					'class'   => 'ClassB',
					'methods' => [ 'm2' => '' ],
				],
			]
		);
		$this->write_config_data_to_file(
			"$line_dir/static-methods.php",
			[
				'wp-includes/class-a.php' => false,
				'wp-includes/class-c.php' => [
					'class'   => 'ClassC',
					'methods' => [ 'm3' => '' ],
				],
			]
		);

		$config = $this->make_config( [
			'config_dir'      => $config_dir,
			'line_config_dir' => $line_dir,
		] );
		$result = $this->call_private_method( 'build_static_methods_config', [], $config );

		$this->assertSame(
			[
				'wp-includes/class-b.php' => [
					'class'   => 'ClassB',
					'methods' => [ 'm2' => '' ],
				],
				'wp-includes/class-c.php' => [
					'class'   => 'ClassC',
					'methods' => [ 'm3' => '' ],
				],
			],
			$result
		);
	}

	public function test__build_funcs_config__applies_moves_and_version_override() {
		$tmp_dir    = $this->make_temp_dir();
		$config_dir = "$tmp_dir/config";
		$line_dir   = "$config_dir/6.6";

		$this->write_config_data_to_file(
			"$config_dir/functions/wp-includes/functions.php",
			[
				'path_is_absolute' => '2.5.0',
			]
		);
		$this->write_config_data_to_file(
			"$config_dir/functions/wp-includes/load.php",
			[
				'absint' => '2.5.0',
			]
		);
		$this->write_config_data_to_file(
			"$config_dir/symbols-moved.php",
			[
				'functions' => [
					'absint' => [
						'moved_in' => '6.7',
						'from'    => 'wp-includes/functions.php',
						'to'      => 'wp-includes/load.php',
					],
				],
			]
		);
		$this->write_config_data_to_file(
			"$line_dir/functions/wp-includes/functions.php",
			[
				'absint' => '2.5.0 mockable',
			]
		);
		$this->write_config_data_to_file(
			"$line_dir/functions/wp-includes/load.php",
			[
				'absint' => false,
			]
		);

		$config = $this->make_config( [
			'config_dir'      => $config_dir,
			'line_config_dir' => $line_dir,
			'wp_version_line' => '6.6',
		] );
		$result = $this->call_private_method( 'build_funcs_config', [], $config );

		$this->assertSame( '2.5.0', $result['wp-includes/functions.php']['path_is_absolute'] );
		$this->assertSame( '2.5.0 mockable', $result['wp-includes/functions.php']['absint'] );
		$this->assertArrayNotHasKey( 'absint', $result['wp-includes/load.php'] );
	}

}

trait Parser_Config__Test_Utils {

	private function call_private_method( string $method, array $args = [], ?Config $config = null ) {
		$config ??= new ReflectionClass( Config::class )->newInstanceWithoutConstructor();

		return Closure::bind(
			fn() => $this->$method( ...$args ),
			$config,
			Config::class
		)();
	}

	private function write_config_data_to_file( string $file, array $data ): void {
		$dir = dirname( $file );
		if( ! is_dir( $dir ) ){
			mkdir( $dir, 0777, true );
		}

		file_put_contents( $file, "<?php\nreturn " . var_export( $data, true ) . ";\n" );
	}

}
