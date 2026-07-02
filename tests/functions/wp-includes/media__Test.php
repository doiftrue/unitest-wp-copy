<?php

class media__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_wp_additional_image_sizes'] = [];
		wp_high_priority_element_flag( true );
	}

	protected function tearDown(): void {
		$GLOBALS['_wp_additional_image_sizes'] = [];
		wp_high_priority_element_flag( true );
		parent::tearDown();
	}

	public function test__image_constrain_size_for_editor() {
		$this->assertSame( [ 400, 200 ], image_constrain_size_for_editor( 800, 400, [ 400, 400 ], 'display' ) );
		$this->assertSame( [ 300, 150 ], image_constrain_size_for_editor( 800, 400, 'medium', 'display' ) );
		$this->assertSame( [ 800, 400 ], image_constrain_size_for_editor( 800, 400, 'full', 'display' ) );
	}

	public function test__image_hwstring() {
		$this->assertSame( 'width="640" height="480" ', image_hwstring( '640px', 480 ) );
		$this->assertSame( '', image_hwstring( 0, null ) );
	}

	public function test__add_image_size() {
		add_image_size( 'card', -320, 180, [ 'left', 'top' ] );
		$this->assertSame( [ 'width' => 320, 'height' => 180, 'crop' => [ 'left', 'top' ] ], $GLOBALS['_wp_additional_image_sizes']['card'] );
	}

	public function test__has_image_size() {
		$this->assertFalse( has_image_size( 'card' ) );
		$GLOBALS['_wp_additional_image_sizes']['card'] = [ 'width' => 320 ];
		$this->assertTrue( has_image_size( 'card' ) );
	}

	public function test__remove_image_size() {
		$this->assertFalse( remove_image_size( 'card' ) );
		$GLOBALS['_wp_additional_image_sizes']['card'] = [ 'width' => 320 ];
		$this->assertTrue( remove_image_size( 'card' ) );
		$this->assertArrayNotHasKey( 'card', $GLOBALS['_wp_additional_image_sizes'] );
	}

	public function test__set_post_thumbnail_size() {
		set_post_thumbnail_size( 640, 360, true );
		$this->assertSame( [ 'width' => 640, 'height' => 360, 'crop' => true ], $GLOBALS['_wp_additional_image_sizes']['post-thumbnail'] );
	}

	public function test__wp_constrain_dimensions() {
		$this->assertSame( [ 800, 400 ], wp_constrain_dimensions( 800, 400 ) );
		$this->assertSame( [ 400, 200 ], wp_constrain_dimensions( 800, 400, 400, 400 ) );
		$this->assertSame( [ 200, 100 ], wp_constrain_dimensions( 800, 400, 300, 100 ) );
	}

	public function test__image_resize_dimensions() {
		$this->assertFalse( image_resize_dimensions( 0, 600, 300, 300 ) );
		$this->assertSame( [ 0, 0, 0, 0, 300, 150, 1200, 600 ], image_resize_dimensions( 1200, 600, 300, 300 ) );
		$this->assertSame( [ 0, 0, 300, 0, 300, 300, 600, 600 ], image_resize_dimensions( 1200, 600, 300, 300, true ) );
	}

	public function test__wp_image_matches_ratio() {
		$this->assertTrue( wp_image_matches_ratio( 1200, 600, 600, 300 ) );
		$this->assertFalse( wp_image_matches_ratio( 1200, 600, 600, 400 ) );
	}

	public function test__get_intermediate_image_sizes() {
		add_image_size( 'card', 320, 180 );
		$this->assertSame( [ 'thumbnail', 'medium', 'medium_large', 'large', 'card' ], get_intermediate_image_sizes() );
	}

	public function test__wp_get_registered_image_subsizes() {
		add_image_size( 'card', 320, 180, [ 'left', 'top' ] );
		$sizes = wp_get_registered_image_subsizes();

		$this->assertSame( [ 'width' => 150, 'height' => 150, 'crop' => true ], $sizes['thumbnail'] );
		$this->assertSame( [ 'width' => 768, 'height' => 0, 'crop' => false ], $sizes['medium_large'] );
		$this->assertSame( [ 'width' => 320, 'height' => 180, 'crop' => [ 'left', 'top' ] ], $sizes['card'] );
	}

	public function test___wp_get_attachment_relative_path() {
		$this->assertSame( '', _wp_get_attachment_relative_path( 'image.jpg' ) );
		$this->assertSame( '2026/07', _wp_get_attachment_relative_path( '/var/www/wp-content/uploads/2026/07/image.jpg' ) );
		$this->assertSame( '/tmp/images', _wp_get_attachment_relative_path( '/tmp/images/image.jpg' ) );
	}

	public function test___wp_get_image_size_from_meta() {
		$meta = [ 'width' => '1200', 'height' => '600', 'sizes' => [ 'card' => [ 'width' => '320', 'height' => '180' ] ] ];
		$this->assertSame( [ 1200, 600 ], _wp_get_image_size_from_meta( 'full', $meta ) );
		$this->assertSame( [ 320, 180 ], _wp_get_image_size_from_meta( 'card', $meta ) );
		$this->assertFalse( _wp_get_image_size_from_meta( 'missing', $meta ) );
	}

	public function test__wp_image_file_matches_image_meta() {
		$meta = [ 'file' => '2026/07/photo.jpg', 'width' => 1200, 'height' => 600 ];
		$this->assertTrue( wp_image_file_matches_image_meta( 'https://example.com/uploads/2026/07/photo.jpg?x=1', $meta ) );
		$this->assertFalse( wp_image_file_matches_image_meta( 'https://example.com/other.jpg', $meta ) );
	}

	public function test__wp_image_src_get_dimensions() {
		$meta = [
			'file'   => '2026/07/photo.jpg',
			'width'  => 1200,
			'height' => 600,
			'sizes'  => [ 'card' => [ 'file' => 'photo-320x180.jpg', 'width' => 320, 'height' => 180 ] ],
		];
		$this->assertSame( [ 1200, 600 ], wp_image_src_get_dimensions( 'https://example.com/photo.jpg', $meta ) );
		$this->assertSame( [ 320, 180 ], wp_image_src_get_dimensions( 'https://example.com/photo-320x180.jpg', $meta ) );
		$this->assertFalse( wp_image_src_get_dimensions( 'https://example.com/missing.jpg', $meta ) );
	}

	public function test__wp_lazy_loading_enabled() {
		$this->assertTrue( wp_lazy_loading_enabled( 'img', 'test' ) );
		$this->assertTrue( wp_lazy_loading_enabled( 'iframe', 'test' ) );
		$this->assertFalse( wp_lazy_loading_enabled( 'video', 'test' ) );
	}

	public function test__wp_img_tag_add_auto_sizes() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "wp_img_tag_add_auto_sizes() not exists on WP $wp_ver" );
		}

		$image = '<img loading="lazy" width="640" sizes="(max-width: 640px) 100vw, 640px" src="x.jpg">';
		$this->assertStringContainsString( 'sizes="auto, (max-width: 640px) 100vw, 640px"', wp_img_tag_add_auto_sizes( $image ) );
		$this->assertSame( '<img src="x.jpg">', wp_img_tag_add_auto_sizes( '<img src="x.jpg">' ) );
	}

	public function test__wp_sizes_attribute_includes_valid_auto() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "wp_sizes_attribute_includes_valid_auto() not exists on WP $wp_ver" );
		}

		$this->assertTrue( wp_sizes_attribute_includes_valid_auto( " \tauto, 100vw" ) );
		$this->assertFalse( wp_sizes_attribute_includes_valid_auto( '100vw, auto' ) );
	}

	public function test___wp_post_thumbnail_class_filter() {
		$this->assertSame( [ 'class' => 'attachment wp-post-image' ], _wp_post_thumbnail_class_filter( [ 'class' => 'attachment' ] ) );
	}

	public function test___wp_post_thumbnail_class_filter_add() {
		_wp_post_thumbnail_class_filter_add( [] );
		$this->assertNotFalse( has_filter( 'wp_get_attachment_image_attributes', '_wp_post_thumbnail_class_filter' ) );
		remove_filter( 'wp_get_attachment_image_attributes', '_wp_post_thumbnail_class_filter' );
	}

	public function test___wp_post_thumbnail_class_filter_remove() {
		add_filter( 'wp_get_attachment_image_attributes', '_wp_post_thumbnail_class_filter' );
		_wp_post_thumbnail_class_filter_remove( [] );
		$this->assertFalse( has_filter( 'wp_get_attachment_image_attributes', '_wp_post_thumbnail_class_filter' ) );
	}

	public function test___wp_post_thumbnail_context_filter() {
		$this->assertSame( 'the_post_thumbnail', _wp_post_thumbnail_context_filter( 'other' ) );
	}

	public function test___wp_post_thumbnail_context_filter_add() {
		_wp_post_thumbnail_context_filter_add();
		$this->assertNotFalse( has_filter( 'wp_get_attachment_image_context', '_wp_post_thumbnail_context_filter' ) );
		remove_filter( 'wp_get_attachment_image_context', '_wp_post_thumbnail_context_filter' );
	}

	public function test___wp_post_thumbnail_context_filter_remove() {
		add_filter( 'wp_get_attachment_image_context', '_wp_post_thumbnail_context_filter' );
		_wp_post_thumbnail_context_filter_remove();
		$this->assertFalse( has_filter( 'wp_get_attachment_image_context', '_wp_post_thumbnail_context_filter' ) );
	}

	public function test__wp_mediaelement_fallback() {
		$this->assertSame( '<a href="https://example.com/a.mp3">https://example.com/a.mp3</a>', wp_mediaelement_fallback( 'https://example.com/a.mp3' ) );
	}

	public function test__wp_get_audio_extensions() {
		$this->assertSame( [ 'mp3', 'ogg', 'flac', 'm4a', 'wav' ], wp_get_audio_extensions() );
	}

	public function test__wp_get_attachment_id3_keys() {
		$this->assertSame( [ 'artist', 'album', 'genre', 'year', 'length_formatted' ], array_keys( wp_get_attachment_id3_keys( (object) [] ) ) );
		$this->assertSame( [ 'artist', 'album', 'bitrate', 'bitrate_mode' ], array_keys( wp_get_attachment_id3_keys( (object) [], 'js' ) ) );
	}

	public function test__wp_get_video_extensions() {
		$this->assertSame( [ 'mp4', 'm4v', 'webm', 'ogv', 'flv' ], wp_get_video_extensions() );
	}

	public function test__wp_expand_dimensions() {
		$this->assertSame( [ 800, 450 ], wp_expand_dimensions( 16, 9, 800, 600 ) );
	}

	public function test__wp_max_upload_size() {
		$this->assertGreaterThan( 0, wp_max_upload_size() );
	}

	public function test__get_media_embedded_in_content() {
		$content = '<p>x</p><audio src="a.mp3"></audio><iframe src="x"></iframe><video src="v.mp4" />';
		$this->assertCount( 3, get_media_embedded_in_content( $content ) );
		$this->assertSame( [ '<iframe src="x"></iframe>' ], get_media_embedded_in_content( $content, 'iframe' ) );
	}

	public function test___wp_add_additional_image_sizes() {
		_wp_add_additional_image_sizes();
		$this->assertSame( 1536, $GLOBALS['_wp_additional_image_sizes']['1536x1536']['width'] );
		$this->assertSame( 2048, $GLOBALS['_wp_additional_image_sizes']['2048x2048']['height'] );
	}

	public function test__wp_omit_loading_attr_threshold() {
		$this->assertSame( 3, wp_omit_loading_attr_threshold( true ) );
		$this->assertSame( 3, wp_omit_loading_attr_threshold() );
	}

	public function test__wp_increase_content_media_count() {
		$before = wp_increase_content_media_count( 0 );
		$this->assertSame( $before + 2, wp_increase_content_media_count( 2 ) );
	}

	public function test__wp_maybe_add_fetchpriority_high_attr() {
		$this->assertSame( [], wp_maybe_add_fetchpriority_high_attr( [], 'iframe', [ 'width' => 800, 'height' => 600 ] ) );
		$this->assertSame( [ 'fetchpriority' => 'high' ], wp_maybe_add_fetchpriority_high_attr( [], 'img', [ 'width' => 800, 'height' => 600 ] ) );
		$this->assertSame( [ 'loading' => 'lazy' ], wp_maybe_add_fetchpriority_high_attr( [ 'loading' => 'lazy' ], 'img', [ 'width' => 800, 'height' => 600 ] ) );
	}

	public function test__wp_high_priority_element_flag() {
		$this->assertTrue( wp_high_priority_element_flag() );
		$this->assertFalse( wp_high_priority_element_flag( false ) );
		$this->assertTrue( wp_high_priority_element_flag( true ) );
	}

	public function test__wp_get_image_editor_output_format() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "wp_get_image_editor_output_format() not exists on WP $wp_ver" );
		}

		$formats = wp_get_image_editor_output_format( 'photo.heic', 'image/heic' );
		$this->assertSame( 'image/jpeg', $formats['image/heic'] );
		$this->assertArrayNotHasKey( 'image/png', $formats );
	}
}
