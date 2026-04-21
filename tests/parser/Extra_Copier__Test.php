<?php

use Parser\Extra_Copier;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Extra_Copier__Test extends Project_TestCase {

	public function test__run__copies_version_file_to_current_wp_line_dir(): void {
		$tmp_dir = $this->make_temp_dir( 'extra-copier-test' );
		$wp_core_dir = "$tmp_dir/wp-core";
		$runtime_dir = "$tmp_dir/wp-runtime";

		$src_file = "$wp_core_dir/wp-includes/version.php";
		mkdir( dirname( $src_file ), 0777, true );
		file_put_contents( $src_file, "<?php\n\$wp_version = '9.9.9';\n" );
		file_put_contents(
			"$wp_core_dir/wp-includes/kses.php",
			<<<'PHP'
			<?php
			global $allowedposttags;
			$allowedposttags = array( 'a' => array() );
			
			/**
			 * Function docs.
			 */
			function wp_kses() {}
			PHP
		);

		$config = $this->make_config( [
			'wp_core_dir' => $wp_core_dir,
			'runtime_dir' => $runtime_dir,
			'wp_version_line' => '9.9',
		] );

		$copier = new Extra_Copier( $config );
		$copier->run();

		$dest_file = "$runtime_dir/wp-line-extra/9.9/wp-includes/version.php";
		$init_part_file = "$runtime_dir/wp-line-extra/9.9/init-parts/wp-includes/kses.php";

		$this->assertFileExists( $dest_file );
		$this->assertFileExists( $init_part_file );
		$this->assertSame( file_get_contents( $src_file ), file_get_contents( $dest_file ) );
		$this->assertSame(
			<<<'PHP'
			<?php
			global $allowedposttags;
			$allowedposttags = array( 'a' => array() );
			
			/**
			 * Function docs.
			 */
			PHP . "\n",
			file_get_contents( $init_part_file )
		);
	}

	public function test__run__throws_when_kses_has_no_function(): void {
		$tmp_dir = $this->make_temp_dir( 'extra-copier-test' );
		$wp_core_dir = "$tmp_dir/wp-core";

		$version_file = "$wp_core_dir/wp-includes/version.php";
		mkdir( dirname( $version_file ), 0777, true );
		file_put_contents( $version_file, "<?php\n\$wp_version = '9.9.9';\n" );
		file_put_contents( "$wp_core_dir/wp-includes/kses.php", "<?php\n\$x = 1;\n" );

		$config = $this->make_config( [
			'wp_core_dir' => $wp_core_dir,
			'runtime_dir' => "$tmp_dir/wp-runtime",
			'wp_version_line' => '9.9',
		] );

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Not found first function in extra file `wp-includes/kses.php`' );

		$copier = new Extra_Copier( $config );
		$copier->run();
	}

}
