<?php

use PHPUnit\Framework\TestCase;

class WP_HTML_Doctype_Info__Test extends TestCase {

	public function test__from_doctype_token() {
		$doctype = WP_HTML_Doctype_Info::from_doctype_token( '<!DOCTYPE html>' );

		$this->assertInstanceOf( WP_HTML_Doctype_Info::class, $doctype );
		$this->assertSame( 'html', $doctype->name );
		$this->assertSame( 'no-quirks', $doctype->indicated_compatability_mode );

		$this->assertNull( WP_HTML_Doctype_Info::from_doctype_token( 'html' ) );
	}
}

