<?php

class blocks__Test extends \PHPUnit\Framework\TestCase {

	public function test__remove_block_asset_path_prefix() {
		$this->assertSame( 'build/view.js', remove_block_asset_path_prefix( 'file:./build/view.js' ) );
		$this->assertSame( 'handle', remove_block_asset_path_prefix( 'handle' ) );
	}

	public function test__generate_block_asset_handle() {
		$this->assertSame( 'wp-block-group-view-2', generate_block_asset_handle( 'core/group', 'viewScript', 1 ) );
		$this->assertSame( 'vendor-card-editor-style', generate_block_asset_handle( 'vendor/card', 'editorStyle' ) );
	}

	public function test__unregister_block_type() {
		$registry = WP_Block_Type_Registry::get_instance();
		$registry->register( 'unitest/unregister' );
		$this->assertInstanceOf( WP_Block_Type::class, unregister_block_type( 'unitest/unregister' ) );
	}

	public function test__get_dynamic_block_names() {
		$registry = WP_Block_Type_Registry::get_instance();
		$registry->register( 'unitest/dynamic', [ 'render_callback' => static fn() => '' ] );
		$this->assertContains( 'unitest/dynamic', get_dynamic_block_names() );
		$registry->unregister( 'unitest/dynamic' );
	}

	public function test__get_hooked_blocks() {
		$registry = WP_Block_Type_Registry::get_instance();
		$registry->register( 'unitest/hooked', [ 'block_hooks' => [ 'core/paragraph' => 'after' ] ] );
		$this->assertContains( 'unitest/hooked', get_hooked_blocks()['core/paragraph']['after'] );
		$registry->unregister( 'unitest/hooked' );
	}

	public function test__insert_hooked_blocks() {
		$anchor = [ 'blockName' => 'core/paragraph', 'attrs' => [] ];
		$markup = insert_hooked_blocks( $anchor, 'after', [ 'core/paragraph' => [ 'after' => [ 'unitest/hooked' ] ] ], [] );
		$this->assertSame( '<!-- wp:unitest/hooked /-->', $markup );
	}

	public function test__set_ignored_hooked_blocks_metadata() {
		$anchor = [ 'blockName' => 'core/paragraph', 'attrs' => [] ];
		$this->assertSame( '', set_ignored_hooked_blocks_metadata( $anchor, 'after', [ 'core/paragraph' => [ 'after' => [ 'unitest/hooked' ] ] ], [] ) );
		$this->assertSame( [ 'unitest/hooked' ], $anchor['attrs']['metadata']['ignoredHookedBlocks'] );
	}

	public function test__remove_serialized_parent_block() {
		if( $wp_ver = wp_version_compare( '< 6.6.0' ) ){
			$this->markTestSkipped( "remove_serialized_parent_block() not exists on WP $wp_ver" );
		}

		$this->assertSame( '<p>Text</p>', remove_serialized_parent_block( '<!-- wp:group --><p>Text</p><!-- /wp:group -->' ) );
	}

	public function test__extract_serialized_parent_block() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "extract_serialized_parent_block() not exists on WP $wp_ver" );
		}

		$this->assertSame( '<!-- wp:group --><!-- /wp:group -->', extract_serialized_parent_block( '<!-- wp:group --><p>Text</p><!-- /wp:group -->' ) );
	}

	public function test__insert_hooked_blocks_and_set_ignored_hooked_blocks_metadata() {
		if( $wp_ver = wp_version_compare( '< 6.6.0' ) ){
			$this->markTestSkipped( "insert_hooked_blocks_and_set_ignored_hooked_blocks_metadata() not exists on WP $wp_ver" );
		}

		$anchor = [ 'blockName' => 'core/paragraph', 'attrs' => [] ];
		$markup = insert_hooked_blocks_and_set_ignored_hooked_blocks_metadata( $anchor, 'after', [ 'core/paragraph' => [ 'after' => [ 'unitest/hooked' ] ] ], [] );
		$this->assertSame( '<!-- wp:unitest/hooked /-->', $markup );
		$this->assertSame( [ 'unitest/hooked' ], $anchor['attrs']['metadata']['ignoredHookedBlocks'] );
	}

	public function test__make_after_block_visitor() {
		$visitor = make_after_block_visitor( [], [], static fn( &$block, $position ) => $position );
		$block = [];
		$this->assertSame( 'after', $visitor( $block ) );
	}

	public function test__serialize_block_attributes() {
		$this->assertSame(
			'{"text":"a\\u002d\\u002db\\u003cc\\u0026d\\u003ee"}',
			serialize_block_attributes( [ 'text' => 'a--b<c&d>e' ] )
		);
	}

	public function test__strip_core_block_namespace() {
		$this->assertSame( 'paragraph', strip_core_block_namespace( 'core/paragraph' ) );
		$this->assertSame( 'plugin/block', strip_core_block_namespace( 'plugin/block' ) );
		$this->assertNull( strip_core_block_namespace() );
	}

	public function test__get_comment_delimited_block_content() {
		$this->assertSame( 'raw', get_comment_delimited_block_content( null, [], 'raw' ) );
		$this->assertSame(
			'<!-- wp:paragraph {"align":"center"} -->text<!-- /wp:paragraph -->',
			get_comment_delimited_block_content( 'core/paragraph', [ 'align' => 'center' ], 'text' )
		);
		$this->assertSame( '<!-- wp:separator /-->', get_comment_delimited_block_content( 'core/separator', [], '' ) );
	}

	public function test__serialize_block() {
		$block = [
			'blockName'    => 'core/group',
			'attrs'        => null,
			'innerBlocks'  => [ [
				'blockName' => 'core/paragraph', 'attrs' => [], 'innerBlocks' => [],
				'innerHTML' => '<p>Text</p>', 'innerContent' => [ '<p>Text</p>' ],
			] ],
			'innerHTML'    => '<div><p>Text</p></div>',
			'innerContent' => [ '<div>', null, '</div>' ],
		];

		$this->assertSame(
			'<!-- wp:group --><div><!-- wp:paragraph --><p>Text</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			serialize_block( $block )
		);
	}

	public function test__serialize_blocks() {
		$blocks = [ [
			'blockName' => null, 'attrs' => [], 'innerBlocks' => [],
			'innerHTML' => 'Before', 'innerContent' => [ 'Before' ],
		], [
			'blockName' => 'core/image', 'attrs' => [ 'id' => 7 ], 'innerBlocks' => [],
			'innerHTML' => '', 'innerContent' => [],
		] ];

		$this->assertSame( 'Before<!-- wp:image {"id":7} /-->', serialize_blocks( $blocks ) );
	}

	public function test__traverse_and_serialize_block() {
		$block = parse_blocks( '<!-- wp:group --><!-- wp:paragraph --><p>Text</p><!-- /wp:paragraph --><!-- /wp:group -->' )[0];
		$this->assertStringContainsString( 'before', traverse_and_serialize_block( $block, static fn() => 'before' ) );
	}

	public function test__traverse_and_serialize_blocks() {
		$blocks = parse_blocks( '<!-- wp:paragraph --><p>Text</p><!-- /wp:paragraph -->' );
		$this->assertStringStartsWith( 'before', traverse_and_serialize_blocks( $blocks, static fn() => 'before' ) );
	}

	public function test__filter_block_content() {
		$content = '<!-- wp:test {"html":"<script>bad</script><b>good</b>"} /-->';
		$this->assertStringNotContainsString( '<script>', filter_block_content( $content, [ 'b' => [] ] ) );
	}

	public function test___filter_block_content_callback() {
		$this->assertSame( '<!--value-->', _filter_block_content_callback( [ '', 'value---' ] ) );
	}

	public function test__filter_block_kses() {
		$block = [ 'attrs' => [ 'html' => '<i>yes</i><b>no</b>' ], 'innerBlocks' => [] ];
		$this->assertSame( '<i>yes</i>no', filter_block_kses( $block, [ 'i' => [] ] )['attrs']['html'] );
	}

	public function test__filter_block_kses_value() {
		$this->assertSame( [ 'key' => '<em>value</em>x' ], filter_block_kses_value( [ 'key' => '<em>value</em><script>x</script>' ], [ 'em' => [] ] ) );
	}

	public function test__filter_block_core_template_part_attributes() {
		$this->assertSame( 'header', filter_block_core_template_part_attributes( 'header', 'slug', [] ) );
		$this->assertSame( '', filter_block_core_template_part_attributes( 'script', 'tagName', [ 'div' => [] ] ) );
	}

	public function test__excerpt_remove_footnotes() {
		$this->assertSame( 'Text', excerpt_remove_footnotes( 'Text<sup data-fn="1" class="fn"><a href="#1" id="1">1</a></sup>' ) );
	}

	public function test__parse_blocks() {
		$blocks = parse_blocks( 'Before<!-- wp:paragraph --><p>Text</p><!-- /wp:paragraph -->After' );

		$this->assertCount( 3, $blocks );
		$this->assertSame( 'core/paragraph', $blocks[1]['blockName'] );
		$this->assertSame( '<p>Text</p>', $blocks[1]['innerHTML'] );
	}

	public function test___restore_wpautop_hook() {
		add_filter( 'the_content', '_restore_wpautop_hook', 11 );
		$this->assertSame( 'content', _restore_wpautop_hook( 'content' ) );
		$this->assertSame( 10, has_filter( 'the_content', 'wpautop' ) );
		remove_filter( 'the_content', 'wpautop', 10 );
	}

	public function test__register_block_style() {
		$this->assertTrue( register_block_style( 'unitest/block', [ 'name' => 'accent', 'label' => 'Accent' ] ) );
		unregister_block_style( 'unitest/block', 'accent' );
	}

	public function test__unregister_block_style() {
		register_block_style( 'unitest/block', [ 'name' => 'remove', 'label' => 'Remove' ] );
		$this->assertTrue( unregister_block_style( 'unitest/block', 'remove' ) );
	}

	public function test__block_has_support() {
		$block_type = new WP_Block_Type( 'unitest/support', [ 'supports' => [ 'color' => [ 'text' => true ] ] ] );
		$this->assertTrue( block_has_support( $block_type, [ 'color', 'text' ] ) );
		$this->assertFalse( block_has_support( null, 'color' ) );
	}

	public function test__wp_migrate_old_typography_shape() {
		$metadata = [ 'name' => 'unitest/block', 'supports' => [ 'typography' => [ 'fontSize' => true ] ] ];
		$this->assertSame( $metadata, wp_migrate_old_typography_shape( $metadata ) );
	}

	public function test__get_query_pagination_arrow() {
		$block = (object) [ 'context' => [ 'paginationArrow' => 'arrow' ] ];
		$this->assertStringContainsString( '→', get_query_pagination_arrow( $block, true ) );
	}

	public function test__get_comments_pagination_arrow() {
		$block = (object) [ 'context' => [ 'comments/paginationArrow' => 'chevron' ] ];
		$this->assertStringContainsString( '«', get_comments_pagination_arrow( $block, 'previous' ) );
	}

	public function test___wp_filter_post_meta_footnotes() {
		$input = wp_slash( wp_json_encode( [ [ 'id' => 'Note 1', 'content' => '<b>Text</b><script>bad</script>' ] ] ) );
		$this->assertStringNotContainsString( '<script>', _wp_filter_post_meta_footnotes( $input ) );
	}

	public function test___wp_footnotes_kses_init_filters() {
		_wp_footnotes_kses_init_filters();
		$this->assertNotFalse( has_filter( 'sanitize_post_meta_footnotes', '_wp_filter_post_meta_footnotes' ) );
		_wp_footnotes_remove_filters();
	}

	public function test___wp_footnotes_remove_filters() {
		_wp_footnotes_kses_init_filters();
		_wp_footnotes_remove_filters();
		$this->assertFalse( has_filter( 'sanitize_post_meta_footnotes', '_wp_filter_post_meta_footnotes' ) );
	}

	public function test___wp_footnotes_force_filtered_html_on_import_filter() {
		$this->assertTrue( _wp_footnotes_force_filtered_html_on_import_filter( true ) );
		$this->assertNotFalse( has_filter( 'sanitize_post_meta_footnotes', '_wp_filter_post_meta_footnotes' ) );
		_wp_footnotes_remove_filters();
	}

}
