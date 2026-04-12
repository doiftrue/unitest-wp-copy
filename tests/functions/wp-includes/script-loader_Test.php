<?php

class script_loader_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['_wp_theme_features'] = [];
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	public function test__wp_sanitize_script_attributes() {
		$attrs = wp_sanitize_script_attributes( [
			'async' => true,
			'id'    => 'test-id',
		] );

		$this->assertStringContainsString( 'async="async"', $attrs );
		$this->assertStringContainsString( 'id="test-id"', $attrs );
	}

	public function test__wp_get_script_tag() {
		$tag = wp_get_script_tag( [ 'src' => '/assets/app.js' ] );

		$this->assertStringContainsString( '<script', $tag );
		$this->assertStringContainsString( 'src="/assets/app.js"', $tag );
	}

	public function test__wp_print_script_tag() {
		ob_start();
		wp_print_script_tag( [ 'src' => '/assets/printed.js' ] );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'printed.js', $output );
	}

	public function test__wp_get_inline_script_tag() {
		$tag = wp_get_inline_script_tag( 'var test = 1;', [ 'id' => 'inline-id' ] );

		$this->assertStringContainsString( '<script', $tag );
		$this->assertStringContainsString( 'inline-id', $tag );
		$this->assertStringContainsString( 'var test = 1;', $tag );
	}

	public function test__wp_print_inline_script_tag() {
		ob_start();
		wp_print_inline_script_tag( 'var printed = 1;', [ 'id' => 'printed-inline' ] );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'printed-inline', $output );
		$this->assertStringContainsString( 'var printed = 1;', $output );
	}

	public function test___print_scripts() {
		$GLOBALS['compress_scripts'] = false;
		$GLOBALS['wp_scripts'] = new WP_Scripts();
		$GLOBALS['wp_scripts']->print_html = "<script id='ready-js'></script>\n";

		ob_start();
		_print_scripts();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'ready-js', $output );
	}

}
