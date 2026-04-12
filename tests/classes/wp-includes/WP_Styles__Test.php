<?php

class WP_Styles__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['_wp_theme_features'] = [];
	}

	public function test__public_methods() {
		$styles = new WP_Styles();
		$this->assertTrue( $styles->add( 'class-style', '/assets/class.css', [], null, 'all' ) );
		$this->assertTrue( $styles->add_inline_style( 'class-style', 'body{background:#fff;}' ) );

		$styles->enqueue( 'class-style' );

		ob_start();
		$done = $styles->do_items( [ 'class-style' ] );
		$output = ob_get_clean();

		$this->assertContains( 'class-style', $done );
		$this->assertStringContainsString( '<link', $output );
		$this->assertStringContainsString( 'background:#fff;', $output );
	}

}
