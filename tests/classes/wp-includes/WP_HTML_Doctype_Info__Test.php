<?php

class WP_HTML_Doctype_Info__Test extends \PHPUnit\Framework\TestCase {

	public function test__from_doctype_token() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "WP_HTML_Doctype_Info{} not exists on WP $wp_ver" );
			return;
		}

		$doctype = WP_HTML_Doctype_Info::from_doctype_token( '<!DOCTYPE html>' );

		$this->assertInstanceOf( WP_HTML_Doctype_Info::class, $doctype );
		$this->assertSame( 'html', $doctype->name );
		$this->assertSame( 'no-quirks', wp_version_compare( '>= 6.9' ) ? $doctype->indicated_compatibility_mode : $doctype->indicated_compatability_mode );

		$this->assertNull( WP_HTML_Doctype_Info::from_doctype_token( 'html' ) );
	}
}

