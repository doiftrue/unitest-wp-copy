<?php

use Parser\Extra_Copier;

require_once __DIR__ . '/Parser_TestCase.php';

class Extra_Copier__Test extends Parser_TestCase {

	public function test__run__copies_version_file_to_current_wp_line_dir(): void {
		$tmp_dir = $this->make_temp_dir( 'extra-copier-test' );
		$wp_core_dir = "$tmp_dir/wp-core";
		$runtime_dir = "$tmp_dir/wp-runtime";

		$src_file = "$wp_core_dir/wp-includes/version.php";
		mkdir( dirname( $src_file ), 0777, true );
		file_put_contents( $src_file, "<?php\n\$wp_version = '9.9.9';\n" );

		$config = $this->make_config( [
			'wp_core_dir' => $wp_core_dir,
			'runtime_dir' => $runtime_dir,
			'wp_version_line' => '9.9',
		] );

		$copier = new Extra_Copier( $config );
		$copier->run();

		$dest_file = "$runtime_dir/wp-line-extra/9.9/wp-includes/version.php";

		$this->assertFileExists( $dest_file );
		$this->assertSame( file_get_contents( $src_file ), file_get_contents( $dest_file ) );
	}

	public function test__run__throws_when_source_file_is_missing(): void {
		$tmp_dir = $this->make_temp_dir( 'extra-copier-test' );

		$config = $this->make_config( [
			'wp_core_dir' => "$tmp_dir/wp-core",
			'runtime_dir' => "$tmp_dir/wp-runtime",
			'wp_version_line' => '9.9',
		] );

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Not found extra file `wp-includes/version.php`' );

		$copier = new Extra_Copier( $config );
		$copier->run();
	}

}
