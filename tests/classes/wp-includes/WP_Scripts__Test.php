<?php

class WP_Scripts__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	public function test__public_methods() {
		$scripts = new WP_Scripts();
		$this->assertTrue( $scripts->add( 'class-script', '/assets/class.js', [], null ) );
		$this->assertTrue( $scripts->add_inline_script( 'class-script', 'console.log("class");' ) );
		$this->assertTrue( $scripts->localize( 'class-script', 'ClassCfg', [ 'enabled' => 'yes' ] ) );

		$scripts->enqueue( 'class-script' );

		ob_start();
		$done = $scripts->do_items( [ 'class-script' ] );
		$output = ob_get_clean();

		$this->assertContains( 'class-script', $done );
		$this->assertStringContainsString( '<script', $output );
	}

}
