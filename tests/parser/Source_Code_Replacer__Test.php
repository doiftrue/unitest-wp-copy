<?php

use Parser\Source_Code_Replacer;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Source_Code_Replacer__Test extends Project_TestCase {

	public function test__replace_in_code__maps_new_stub_option_replacements(): void {
		$replacer = new Source_Code_Replacer(
			$this->make_config( [
				'static_methods_data' => [],
			] )
		);

		$input = <<<'PHP'
		<?php
		$a = get_option( 'html_type' );
		$b = get_option( 'blogdescription' );
		$c = get_option( 'admin_email' );
		$d = get_option( 'stylesheet' );
		$e = get_option( 'template' );
		PHP;

		$output = $replacer->replace_in_code( $input );

		$this->assertStringContainsString( "\$GLOBALS['stub_wp_options']->html_type", $output );
		$this->assertStringContainsString( "\$GLOBALS['stub_wp_options']->blogdescription", $output );
		$this->assertStringContainsString( "\$GLOBALS['stub_wp_options']->admin_email", $output );
		$this->assertStringContainsString( "\$GLOBALS['stub_wp_options']->stylesheet", $output );
		$this->assertStringContainsString( "\$GLOBALS['stub_wp_options']->template", $output );

		$this->assertStringNotContainsString( "get_option( 'html_type' )", $output );
		$this->assertStringNotContainsString( "get_option( 'blogdescription' )", $output );
		$this->assertStringNotContainsString( "get_option( 'admin_email' )", $output );
		$this->assertStringNotContainsString( "get_option( 'stylesheet' )", $output );
		$this->assertStringNotContainsString( "get_option( 'template' )", $output );
	}
}
