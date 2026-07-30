<?php

class WP_Font_Utils__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$this->assertSame( '"Open Sans", serif', WP_Font_Utils::sanitize_font_family( 'Open Sans, serif' ) );
		$this->assertSame(
			'open sans;normal;700;100%;U+0-10FFFF',
			WP_Font_Utils::get_font_face_slug( [ 'fontFamily' => 'Open Sans', 'fontWeight' => 'bold' ] )
		);
	}
}
