<?php

use Parser\Copied_Lister;

require_once __DIR__ . '/Parser_TestCase.php';

class Copied_Lister__Test extends Parser_TestCase {

	public function test__generate_list__ignores_wp_major_minor_mock_files(): void {
		$tmp_dir = $this->make_temp_dir( 'copied-lister-test' );

		$runtime_dir = "$tmp_dir/wp-runtime";
		$copy_dir = "$runtime_dir/copy";
		$mocks_dir = "$runtime_dir/mocks";
		$mockable_dir = "$copy_dir/mockable";

		mkdir( "$mocks_dir/wp-includes", 0777, true );
		mkdir( "$mockable_dir/wp-includes", 0777, true );

		file_put_contents(
			"$mocks_dir/wp-6.5.php",
			"<?php\nfunction old_line_only_symbol() {}\n"
		);
		file_put_contents(
			"$mocks_dir/wp-includes/l10n.php",
			"<?php\nfunction runtime_mock_symbol() {}\n"
		);
		file_put_contents(
			"$mockable_dir/wp-includes/load.php",
			"<?php\nfunction copied_mockable_symbol() {}\n"
		);

		$config = $this->make_config( [
			'runtime_dir' => $runtime_dir,
			'copy_dir' => $copy_dir,
			'wp_version' => '6.9.4',
		] );

		$lister = new Copied_Lister( $config );
		$lister->names = [
			'copied_regular_symbol()',
			'copied_mockable_symbol()',
		];

		$lister->generate_list();

		$doc = file_get_contents( "$runtime_dir/SYMBOLS-INFO.md" );

		$this->assertStringContainsString( 'runtime_mock_symbol()', $doc );
		$this->assertStringContainsString( 'copied_mockable_symbol()', $doc );
		$this->assertStringContainsString( 'copied_regular_symbol()', $doc );
		$this->assertStringNotContainsString( 'old_line_only_symbol()', $doc );
	}

}
