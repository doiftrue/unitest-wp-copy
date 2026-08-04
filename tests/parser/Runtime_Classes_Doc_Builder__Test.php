<?php

use Parser\Runtime_Classes_Doc_Builder;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Runtime_Classes_Doc_Builder__Test extends Project_TestCase {

	public function test__build(): void {
		$tmp_dir = $this->make_temp_dir( 'runtime-classes-lister-test' );

		$mocks_dir = "$tmp_dir/custom-mocks";
		$traits_dir = "$tmp_dir/traits";

		mkdir( "$mocks_dir/wp-includes", 0777, true );
		mkdir( $traits_dir, 0777, true );

		file_put_contents(
			"$mocks_dir/wp-includes/class-runtime-thing.php",
			<<<'PHP'
			<?php
			/**
			 * Runtime thing summary line.
			 */

			namespace Unitest_WP_Copy;

			class Runtime_Thing {

				use thing__Copied_Methods;

				public string $table = 'wp_table';

				private bool $flag = true;

				public function adapted_method( $data ) {}

				private function hidden_method() {}

			}
			PHP
		);

		file_put_contents(
			"$traits_dir/thing__Copied_Methods.php",
			"<?php\nnamespace Unitest_WP_Copy;\ntrait thing__Copied_Methods {\n\tpublic function copied_method() {}\n\tprivate function internal_method() {}\n}\n"
		);

		$doc = new Runtime_Classes_Doc_Builder( $mocks_dir, $traits_dir )->build();

		$this->assertStringContainsString( '\Unitest_WP_Copy\Runtime_Thing', $doc );
		$this->assertStringContainsString( 'Runtime thing summary line.', $doc );
		$this->assertMatchesRegularExpression( '~adapted_method\(\)\s+\[adapted]~', $doc );
		$this->assertMatchesRegularExpression( '~copied_method\(\)\s+\[wp]~', $doc );
		$this->assertStringContainsString( '$table', $doc );

		$this->assertStringNotContainsString( 'hidden_method', $doc );
		$this->assertStringNotContainsString( 'internal_method', $doc );
		$this->assertStringNotContainsString( '$flag', $doc );
	}

	public function test__build__class_docblock_wins_over_file_docblock(): void {
		$tmp_dir = $this->make_temp_dir( 'runtime-classes-lister-doc-test' );

		$mocks_dir = "$tmp_dir/custom-mocks";
		mkdir( $mocks_dir, 0777, true );

		file_put_contents(
			"$mocks_dir/class-documented.php",
			<<<'PHP'
			<?php
			/**
			 * File level summary.
			 *
			 * @package Test
			 */

			namespace Unitest_WP_Copy;

			/**
			 * Class level summary.
			 */
			class Documented {
			}
			PHP
		);

		$doc = new Runtime_Classes_Doc_Builder( $mocks_dir, "$tmp_dir/traits" )->build();

		$this->assertStringContainsString( 'Class level summary.', $doc );
		$this->assertStringNotContainsString( 'File level summary.', $doc );
	}

	public function test__build__no_classes(): void {
		$tmp_dir = $this->make_temp_dir( 'runtime-classes-lister-empty-test' );

		$mocks_dir = "$tmp_dir/custom-mocks";
		mkdir( $mocks_dir, 0777, true );

		file_put_contents( "$mocks_dir/functions.php", "<?php\nfunction plain_mock() {}\n" );

		$this->assertSame( '(none)', new Runtime_Classes_Doc_Builder( $mocks_dir, "$tmp_dir/traits" )->build() );
	}

}
