<?php

class WP_Block_Parser__Test extends \PHPUnit\Framework\TestCase {

	public function test__parse_and_public_methods() {
		$parser = new WP_Block_Parser();
		$doc = 'Before<!-- wp:paragraph {"align":"left"} -->Text<!-- /wp:paragraph -->After';

		$parsed = $parser->parse( $doc );
		$this->assertIsArray( $parsed );
		$this->assertNotEmpty( $parsed );
		$this->assertSame( 'core/paragraph', $parsed[1]['blockName'] ?? null );

		$free = $parser->freeform( 'raw' );
		$this->assertInstanceOf( WP_Block_Parser_Block::class, $free );
		$this->assertSame( 'raw', $free->innerHTML );

		$parser->document = $doc;
		$parser->offset = 0;
		$parser->output = [];
		$parser->stack = [];

		$token = $parser->next_token();
		$this->assertIsArray( $token );

		$parser->add_freeform( 6 );
		$this->assertNotEmpty( $parser->output );
	}
}

