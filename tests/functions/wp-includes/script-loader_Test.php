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

	public function test__wp_prototype_before_jquery() {
		$sorted = wp_prototype_before_jquery( [ 'jquery', 'prototype', 'utils' ] );
		$this->assertSame( [ 'prototype', 'jquery', 'utils' ], array_values( $sorted ) );
	}

	public function test__wp_remove_surrounding_empty_script_tags() {
		$this->assertSame(
			'console.log("ok");',
			wp_remove_surrounding_empty_script_tags( '  <script>console.log("ok");</script>  ' )
		);
	}

	public function test__wp_filter_out_block_nodes() {
		$nodes = [
			[ 'path' => [ 'styles', 'elements' ] ],
			[ 'path' => [ 'styles', 'blocks', 'core/paragraph' ] ],
		];

		$result = wp_filter_out_block_nodes( $nodes );

		$this->assertCount( 1, $result );
		$this->assertSame( [ 'styles', 'elements' ], array_values( $result )[0]['path'] );
	}

	public function test___wp_normalize_relative_css_links() {
		$css = 'body{background:url(images/bg.png)}';
		$url = 'https://example.com/wp-content/themes/my-theme/style.css';

		$normalized = _wp_normalize_relative_css_links( $css, $url );

		$this->assertStringContainsString( 'url(/wp-content/themes/my-theme/images/bg.png)', $normalized );
	}

}
