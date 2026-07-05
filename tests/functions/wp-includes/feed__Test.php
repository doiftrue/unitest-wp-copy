<?php

class feed__Test extends \PHPUnit\Framework\TestCase {

	public function test__prep_atom_text_construct() {
		$this->assertSame( [ 'text', 'plain text' ], prep_atom_text_construct( 'plain text' ) );
		$this->assertSame(
			[ 'xhtml', "<div xmlns='http://www.w3.org/1999/xhtml'><strong>text</strong></div>" ],
			prep_atom_text_construct( '<strong>text</strong>' )
		);
		$this->assertSame( [ 'html', '<![CDATA[<broken>]]>' ], prep_atom_text_construct( '<broken>' ) );
	}

	public function test__feed_content_type() {
		$this->assertSame( 'application/rss+xml', feed_content_type( 'rss2' ) );
		$this->assertSame( 'application/atom+xml', feed_content_type( 'atom' ) );
		$this->assertSame( 'application/octet-stream', feed_content_type( 'unknown' ) );
	}
}
