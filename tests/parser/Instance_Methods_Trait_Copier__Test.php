<?php

use Parser\BaseStrats\Instance_Methods_Trait_Copier;
use Parser\Symbols_Lister;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Instance_Methods_Trait_Copier__Test extends Project_TestCase {

	public function test__generate_content__copies_selected_methods_into_namespaced_trait() {
		$tmp_dir = $this->make_temp_dir( 'instance-methods-trait-copier-test' );
		$wp_core_dir = "$tmp_dir/wp-core";
		$source_file = "$wp_core_dir/wp-includes/class-source.php";
		mkdir( dirname( $source_file ), 0777, true );
		file_put_contents(
			$source_file,
			<<<'PHP'
			<?php
			class Other_Source {
				public function selected() {
					return 'wrong class';
				}
			}
			class Source_Class {
				private function helper( $value ) {
					return strtoupper( $value );
				}

				public function selected( $value ) {
					return $this->helper( $value );
				}

				public function skipped() {}
			}
			PHP
		);

		$config = $this->make_config( [
			'wp_core_dir' => $wp_core_dir,
			'copy_dir' => "$tmp_dir/wp-runtime/copy",
			'wp_version' => '9.9.0',
			'instance_methods_data' => [
				'wp-includes/class-source.php' => [
					'class'     => 'Source_Class',
					'trait'     => 'Runtime_Methods',
					'methods'   => [
						'helper'   => '1.0.0',
						'selected' => '2.0.0',
					],
				],
			],
		] );
		$copier = new Instance_Methods_Trait_Copier( $config, new Symbols_Lister( $config ) );
		$item = $copier->get_items()[0];
		$content = $copier->generate_content( $item );

		$this->assertSame( "$tmp_dir/wp-runtime/copy/traits/Runtime_Methods.php", $copier->get_dest_file( $item ) );
		$this->assertStringContainsString( 'namespace Unitest_WP_Copy;', $content );
		$this->assertStringContainsString( 'trait Runtime_Methods {', $content );
		$this->assertStringContainsString( 'private function helper( $value )', $content );
		$this->assertStringContainsString( 'public function selected( $value )', $content );
		$this->assertStringNotContainsString( "return 'wrong class';", $content );
		$this->assertStringNotContainsString( 'function skipped()', $content );
	}

	public function test__get_items__skips_methods_added_after_current_wp_version() {
		$config = $this->make_config( [
			'wp_version' => '6.5.0',
			'instance_methods_data' => [
				'source.php' => [
					'class'   => 'Source_Class',
					'trait'   => 'Runtime_Methods',
					'methods' => [
						'available' => '6.5.0',
						'future'    => '6.6.0',
					],
				],
			],
		] );
		$copier = new Instance_Methods_Trait_Copier( $config, new Symbols_Lister( $config ) );

		$this->assertSame( [ 'available' => '6.5.0' ], $copier->get_items()[0]['method_names'] );
	}

	public function test__generate_content__throws_for_missing_configured_method() {
		$tmp_dir = $this->make_temp_dir( 'instance-methods-trait-copier-test' );
		$wp_core_dir = "$tmp_dir/wp-core";
		$source_file = "$wp_core_dir/source.php";
		mkdir( $wp_core_dir, 0777, true );
		file_put_contents( $source_file, "<?php\nclass Source_Class {}\n" );

		$config = $this->make_config( [
			'wp_core_dir' => $wp_core_dir,
			'wp_version' => '9.9.0',
		] );
		$copier = new Instance_Methods_Trait_Copier( $config, new Symbols_Lister( $config ) );

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Not found instance methods in Source_Class' );
		$copier->generate_content( [
			'rel_file' => 'source.php',
			'class_name' => 'Source_Class',
			'trait_name' => 'Runtime_Methods',
			'method_names' => [ 'missing' => '' ],
		] );
	}

}
