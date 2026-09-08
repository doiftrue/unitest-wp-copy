<?php

class WP_Block_Processor__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "WP_Block_Processor not exists on WP $wp_ver" );
		}

		$processor = new WP_Block_Processor(
			'Before<!-- wp:group {"layout":{"type":"constrained"}} -->'
			. '<!-- wp:paragraph /-->'
			. '<!-- /wp:group -->After'
		);

		$this->assertTrue( $processor->next_token() );
		$this->assertTrue( $processor->is_html() );
		$this->assertTrue( $processor->is_non_whitespace_html() );
		$this->assertSame( 'Before', $processor->get_html_content() );
		$this->assertSame( 'core/freeform', $processor->get_printable_block_type() );

		$this->assertTrue( $processor->next_block( 'group' ) );
		$this->assertSame( 'core/group', $processor->get_block_type() );
		$this->assertTrue( $processor->opens_block( 'group' ) );
		$this->assertSame( [ 'core/group' ], $processor->get_breadcrumbs() );
		$this->assertSame( 1, $processor->get_depth() );
		$this->assertSame(
			[ 'layout' => [ 'type' => 'constrained' ] ],
			$processor->allocate_and_return_parsed_attributes()
		);
		$this->assertSame( JSON_ERROR_NONE, $processor->get_last_json_error() );
		$this->assertInstanceOf( WP_HTML_Span::class, $processor->get_span() );

		$this->assertTrue( $processor->next_delimiter( 'paragraph' ) );
		$this->assertFalse( $processor->has_closing_flag() );
		$this->assertSame( 'void', $processor->get_delimiter_type() );

		$this->assertTrue( WP_Block_Processor::are_equal_block_types( 'paragraph', 0, 9, 'core/paragraph', 0, 14 ) );
		$this->assertSame( 'core/paragraph', WP_Block_Processor::normalize_block_type( 'paragraph' ) );

		$extractor = new WP_Block_Processor( '<!-- wp:quote --><p>Text</p><!-- /wp:quote -->' );
		$this->assertTrue( $extractor->next_block() );
		$this->assertSame( 'core/quote', $extractor->extract_full_block_and_advance()['blockName'] );
		$this->assertNull( $extractor->get_last_error() );
	}
}
