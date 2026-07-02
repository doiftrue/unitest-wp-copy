<?php

class embed__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_embed_defaults() {
		$previous = $GLOBALS['content_width'] ?? null;
		unset( $GLOBALS['content_width'] );
		$this->assertSame( [ 'width' => 500, 'height' => 750 ], wp_embed_defaults() );
		$GLOBALS['content_width'] = 800;
		$this->assertSame( [ 'width' => 800, 'height' => 1000 ], wp_embed_defaults( 'https://example.com' ) );
		$GLOBALS['content_width'] = $previous;
	}

	public function test__wp_embed_handler_audio() {
		$this->assertSame( '[audio src="https://example.com/a.mp3" /]', wp_embed_handler_audio( [], [], 'https://example.com/a.mp3', [] ) );
	}

	public function test__wp_embed_handler_video() {
		$this->assertSame( '[video  src="https://example.com/v.mp4" /]', wp_embed_handler_video( [], [], 'https://example.com/v.mp4', [] ) );
		$this->assertSame( '[video width="640" height="360"  src="https://example.com/v.mp4" /]', wp_embed_handler_video( [], [], 'https://example.com/v.mp4', [ 'width' => 640, 'height' => 360 ] ) );
	}

	public function test__wp_maybe_enqueue_oembed_host_js() {
		$this->assertSame( '<p>plain</p>', wp_maybe_enqueue_oembed_host_js( '<p>plain</p>' ) );
		wp_register_script( 'wp-embed', 'https://example.com/wp-embed.js' );
		add_action( 'wp_head', 'wp_oembed_add_host_js' );
		$html = '<blockquote class="wp-embedded-content">embed</blockquote>';
		$this->assertSame( $html, wp_maybe_enqueue_oembed_host_js( $html ) );
		$this->assertTrue( wp_script_is( 'wp-embed', 'enqueued' ) );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		wp_dequeue_script( 'wp-embed' );
		wp_deregister_script( 'wp-embed' );
	}

	public function test__wp_oembed_ensure_format() {
		$this->assertSame( 'json', wp_oembed_ensure_format( 'yaml' ) );
		$this->assertSame( 'json', wp_oembed_ensure_format( 'json' ) );
		$this->assertSame( 'xml', wp_oembed_ensure_format( 'xml' ) );
	}

	public function test___oembed_create_xml() {
		$this->assertFalse( _oembed_create_xml( [] ) );
		$xml = _oembed_create_xml( [ 'title' => 'A & B', 'items' => [ 'one', 'two' ] ] );
		$this->assertStringContainsString( '<title>A &amp; B</title>', $xml );
		$items = simplexml_load_string( $xml )->items->oembed;
		$this->assertCount( 2, $items );
		$this->assertSame( 'one', (string) $items[0] );
		$this->assertSame( 'two', (string) $items[1] );
	}

	public function test__wp_filter_oembed_iframe_title_attribute() {
		$data = (object) [ 'type' => 'video', 'title' => 'A video' ];
		$this->assertSame( false, wp_filter_oembed_iframe_title_attribute( false, $data, 'https://example.com' ) );
		$this->assertSame(
			'<iframe title="A video" src="https://example.com"></iframe>',
			wp_filter_oembed_iframe_title_attribute( '<iframe src="https://example.com"></iframe>', $data, 'https://example.com' )
		);
	}

	public function test___oembed_filter_feed_content() {
		$html = '<iframe class="wp-embedded-content" style="display:none" src="x"></iframe><iframe style="color:red" src="y"></iframe>';
		$result = _oembed_filter_feed_content( $html );
		$this->assertStringNotContainsString( 'display:none', $result );
		$this->assertStringContainsString( 'color:red', $result );
	}
}
