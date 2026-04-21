<?php

use Parser\Symbols_Lister;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Copied_Lister__Test extends Project_TestCase {

	public function test__generate_list(): void {
		$tmp_dir = $this->make_temp_dir( 'copied-lister-test' );

		$runtime_dir = "$tmp_dir/wp-runtime";
		$copy_dir = "$runtime_dir/copy";
		$mocks_dir = "$runtime_dir/mocks";
		$mockable_dir = "$copy_dir/mockable";

		mkdir( "$mocks_dir/wp-includes", 0777, true );
		mkdir( "$mockable_dir/wp-includes", 0777, true );

		file_put_contents(
			"$mocks_dir/wp-includes/l10n.php",
			"<?php\nfunction runtime_mock_symbol() {}\n"
		);
		file_put_contents(
			"$mockable_dir/wp-includes/load.php",
			"<?php\nfunction copied_mockable_symbol() {}\n"
		);

		$lister = new Symbols_Lister( $this->make_config( [
			'runtime_dir' => $runtime_dir,
			'copy_dir' => $copy_dir,
			'wp_version' => '6.9.4',
		] ) );
		$lister->names = [
			'copied_regular_symbol()',
			'copied_mockable_symbol()',
		];

		$lister->generate_list();

		$info = file_get_contents( "$runtime_dir/SYMBOLS-INFO.md" );

		$this->assertStringContainsString( 'runtime_mock_symbol()', $info );
		$this->assertStringContainsString( 'copied_mockable_symbol()', $info );
		$this->assertStringContainsString( 'copied_regular_symbol()', $info );
	}

}
