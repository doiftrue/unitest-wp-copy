<?php

class WP_Interactivity_API_Directives_Processor__Test extends \PHPUnit\Framework\TestCase {

	public function test__balanced_template_content() {
		$processor = new WP_Interactivity_API_Directives_Processor( '<template><b>Text</b></template>' );

		$this->assertTrue( $processor->next_tag( 'TEMPLATE' ) );
		$this->assertSame( '<b>Text</b>', $processor->get_content_between_balanced_template_tags() );
	}
}
