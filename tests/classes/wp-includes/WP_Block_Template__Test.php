<?php

class WP_Block_Template__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_properties() {
		$template = new WP_Block_Template();
		$template->slug = 'single';
		$template->content = '<!-- wp:paragraph /-->';

		$this->assertSame( 'single', $template->slug );
		$this->assertSame( '<!-- wp:paragraph /-->', $template->content );
		$this->assertSame( 'theme', $template->source );
	}
}
