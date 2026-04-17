<?php

class post__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_post_types']          = [];
		$GLOBALS['wp_post_statuses']       = [];
		$GLOBALS['post_type_meta_caps']    = [];
		$GLOBALS['_wp_post_type_features'] = [];
		$GLOBALS['wp_filter']              = [];
		$GLOBALS['wp_actions']             = [];
		$GLOBALS['wp_filters']             = [];
		$GLOBALS['wp_current_filter']      = [];
	}

	public function test__get_extended() {
		$with_more = get_extended( "  Intro\n<!--more Read more-->\nBody" );

		$this->assertSame( 'Intro', $with_more['main'] );
		$this->assertSame( 'Body', $with_more['extended'] );
		$this->assertSame( 'Read more', $with_more['more_text'] );

		$without_more = get_extended( 'Single block' );
		$this->assertSame( 'Single block', $without_more['main'] );
		$this->assertSame( '', $without_more['extended'] );
		$this->assertSame( '', $without_more['more_text'] );
	}

	public function test__get_post_statuses() {
		$statuses = get_post_statuses();

		$this->assertSame( 'Draft', $statuses['draft'] );
		$this->assertSame( 'Pending Review', $statuses['pending'] );
		$this->assertSame( 'Private', $statuses['private'] );
		$this->assertSame( 'Published', $statuses['publish'] );
	}

	public function test__get_page_statuses() {
		$statuses = get_page_statuses();

		$this->assertSame( 'Draft', $statuses['draft'] );
		$this->assertSame( 'Private', $statuses['private'] );
		$this->assertSame( 'Published', $statuses['publish'] );
	}

	public function test___wp_privacy_statuses() {
		$statuses = _wp_privacy_statuses();

		$this->assertSame( 'Pending', $statuses['request-pending'] );
		$this->assertSame( 'Confirmed', $statuses['request-confirmed'] );
		$this->assertSame( 'Failed', $statuses['request-failed'] );
		$this->assertSame( 'Completed', $statuses['request-completed'] );
	}

	public function test__register_post_status() {
		$GLOBALS['wp_post_statuses'] = null;

		$public_status = register_post_status( 'My Status!', [ 'public' => true ] );

		$this->assertSame( 'mystatus', $public_status->name );
		$this->assertTrue( $public_status->public );
		$this->assertFalse( $public_status->internal );
		$this->assertIsArray( $public_status->label_count );
		$this->assertArrayHasKey( 'mystatus', $GLOBALS['wp_post_statuses'] );

		$default_status = register_post_status( 'queue' );
		$this->assertTrue( $default_status->internal );
		$this->assertFalse( $default_status->public );
		$this->assertSame( 'queue', $default_status->label );
	}

	public function test__get_post_status_object() {
		$object = (object) [ 'name' => 'publish' ];
		$GLOBALS['wp_post_statuses']['publish'] = $object;

		$this->assertSame( $object, get_post_status_object( 'publish' ) );
		$this->assertNull( get_post_status_object( 'missing' ) );
	}

	public function test__get_post_stati() {
		$GLOBALS['wp_post_statuses'] = [
			'publish' => (object) [ 'name' => 'publish', 'public' => true ],
			'draft'   => (object) [ 'name' => 'draft', 'public' => false ],
		];

		$public_names = get_post_stati( [ 'public' => true ] );
		$this->assertCount( 1, $public_names );
		$this->assertContains( 'publish', $public_names );

		$objects = get_post_stati( [], 'objects' );
		$this->assertArrayHasKey( 'publish', $objects );
		$this->assertArrayHasKey( 'draft', $objects );
	}

	public function test__is_post_type_hierarchical() {
		$GLOBALS['wp_post_types']['page'] = (object) [
			'name'         => 'page',
			'hierarchical' => true,
		];
		$GLOBALS['wp_post_types']['book'] = (object) [
			'name'         => 'book',
			'hierarchical' => false,
		];

		$this->assertTrue( is_post_type_hierarchical( 'page' ) );
		$this->assertFalse( is_post_type_hierarchical( 'book' ) );
		$this->assertFalse( is_post_type_hierarchical( 'missing' ) );
	}

	public function test__get_post_type_object() {
		$object = (object) [ 'name' => 'book' ];
		$GLOBALS['wp_post_types']['book'] = $object;

		$this->assertSame( $object, get_post_type_object( 'book' ) );
		$this->assertNull( get_post_type_object( 'missing' ) );
	}

	public function test__post_type_exists() {
		$this->assertFalse( post_type_exists( 'book' ) );

		$GLOBALS['wp_post_types']['book'] = (object) [ 'name' => 'book' ];

		$this->assertTrue( post_type_exists( 'book' ) );
	}

	public function test__get_post_types() {
		$GLOBALS['wp_post_types'] = [
			'book'   => (object) [ 'name' => 'book', 'public' => true, 'hierarchical' => false ],
			'page'   => (object) [ 'name' => 'page', 'public' => true, 'hierarchical' => true ],
			'secret' => (object) [ 'name' => 'secret', 'public' => false, 'hierarchical' => false ],
		];

		$public_names = get_post_types( [ 'public' => true ] );
		$this->assertContains( 'book', $public_names );
		$this->assertContains( 'page', $public_names );
		$this->assertNotContains( 'secret', $public_names );

		$hier_objects = get_post_types( [ 'hierarchical' => true ], 'objects' );
		$this->assertArrayHasKey( 'page', $hier_objects );
		$this->assertArrayNotHasKey( 'book', $hier_objects );
	}

	public function test__get_post_type_capabilities() {
		$args = (object) [
			'capability_type' => 'book',
			'map_meta_cap'    => true,
			'capabilities'    => [],
		];

		$caps = get_post_type_capabilities( $args );

		$this->assertSame( 'edit_books', $caps->edit_posts );
		$this->assertSame( 'edit_books', $caps->create_posts );
		$this->assertSame( 'delete_book', $caps->delete_post );
		$this->assertSame( 'read_post', $GLOBALS['post_type_meta_caps']['read_book'] );
		$this->assertSame( 'delete_post', $GLOBALS['post_type_meta_caps']['delete_book'] );
		$this->assertSame( 'edit_post', $GLOBALS['post_type_meta_caps']['edit_book'] );
	}

	public function test___post_type_meta_capabilities() {
		_post_type_meta_capabilities( [
			'read_post'   => 'read_book',
			'delete_post' => 'delete_book',
			'edit_post'   => 'edit_book',
			'edit_posts'  => 'edit_books',
		] );

		$this->assertSame( 'read_post', $GLOBALS['post_type_meta_caps']['read_book'] );
		$this->assertSame( 'delete_post', $GLOBALS['post_type_meta_caps']['delete_book'] );
		$this->assertSame( 'edit_post', $GLOBALS['post_type_meta_caps']['edit_book'] );
		$this->assertArrayNotHasKey( 'edit_books', $GLOBALS['post_type_meta_caps'] );
	}

	public function test___get_custom_object_labels() {
		$data_object = (object) [
			'name'         => 'book',
			'label'        => 'Books',
			'hierarchical' => false,
			'labels'       => (object) [],
		];
		$defaults = [
			'name'          => [ 'Posts', 'Pages' ],
			'singular_name' => [ 'Post', 'Page' ],
			'menu_name'     => [ 'Posts', 'Pages' ],
			'all_items'     => [ 'All Posts', 'All Pages' ],
			'archives'      => [ 'Post Archives', 'Page Archives' ],
		];

		$labels = _get_custom_object_labels( $data_object, $defaults );

		$this->assertSame( 'Books', $labels->name );
		$this->assertSame( 'Books', $labels->singular_name );
		$this->assertSame( 'Books', $labels->name_admin_bar );
		$this->assertSame( 'Books', $labels->menu_name );
		$this->assertSame( 'Books', $labels->all_items );
		$this->assertSame( 'Books', $labels->archives );
		$this->assertInstanceOf( stdClass::class, $data_object->labels );
	}

	public function test__add_post_type_support() {
		add_post_type_support( 'book', 'editor' );
		add_post_type_support( 'book', 'thumbnail', 'large' );
		add_post_type_support( 'book', [ 'excerpt', 'author' ] );

		$this->assertTrue( $GLOBALS['_wp_post_type_features']['book']['editor'] );
		$this->assertSame( [ 'large' ], $GLOBALS['_wp_post_type_features']['book']['thumbnail'] );
		$this->assertTrue( $GLOBALS['_wp_post_type_features']['book']['excerpt'] );
		$this->assertTrue( $GLOBALS['_wp_post_type_features']['book']['author'] );
	}

	public function test__remove_post_type_support() {
		$GLOBALS['_wp_post_type_features']['book'] = [
			'editor'    => true,
			'thumbnail' => true,
		];

		remove_post_type_support( 'book', 'thumbnail' );

		$this->assertTrue( $GLOBALS['_wp_post_type_features']['book']['editor'] );
		$this->assertArrayNotHasKey( 'thumbnail', $GLOBALS['_wp_post_type_features']['book'] );
	}

	public function test__get_all_post_type_supports() {
		$GLOBALS['_wp_post_type_features']['book'] = [
			'editor' => true,
		];

		$this->assertSame( [ 'editor' => true ], get_all_post_type_supports( 'book' ) );
		$this->assertSame( [], get_all_post_type_supports( 'missing' ) );
	}

	public function test__post_type_supports() {
		$GLOBALS['_wp_post_type_features']['book'] = [
			'editor' => true,
		];

		$this->assertTrue( post_type_supports( 'book', 'editor' ) );
		$this->assertFalse( post_type_supports( 'book', 'thumbnail' ) );
	}

	public function test__get_post_types_by_support() {
		$GLOBALS['_wp_post_type_features'] = [
			'book' => [
				'editor'    => true,
				'thumbnail' => true,
			],
			'page' => [
				'editor' => true,
			],
			'note' => [
				'thumbnail' => true,
			],
		];

		$with_editor = get_post_types_by_support( 'editor' );
		$this->assertEqualsCanonicalizing( [ 'book', 'page' ], $with_editor );

		$with_both = get_post_types_by_support( [ 'editor', 'thumbnail' ] );
		$this->assertSame( [ 'book' ], array_values( $with_both ) );

		$without_editor = get_post_types_by_support( 'editor', 'not' );
		$this->assertSame( [ 'note' ], array_values( $without_editor ) );
	}

	public function test__is_post_type_viewable() {
		$GLOBALS['wp_post_types']['book'] = (object) [
			'name'               => 'book',
			'publicly_queryable' => true,
			'_builtin'           => false,
			'public'             => false,
		];

		$this->assertTrue( is_post_type_viewable( 'book' ) );
		$this->assertFalse( is_post_type_viewable( 'missing' ) );

		$builtin_public_type = (object) [
			'name'               => 'post',
			'publicly_queryable' => false,
			'_builtin'           => true,
			'public'             => true,
		];
		$this->assertTrue( is_post_type_viewable( $builtin_public_type ) );

		add_filter(
			'is_post_type_viewable',
			static fn( $is_viewable, $post_type ) => 'yes',
			10,
			2
		);
		$this->assertFalse( is_post_type_viewable( $builtin_public_type ) );
	}

	public function test__is_post_status_viewable() {
		$GLOBALS['wp_post_statuses']['publish'] = (object) [
			'internal'           => false,
			'protected'          => false,
			'publicly_queryable' => true,
			'_builtin'           => true,
			'public'             => true,
		];

		$this->assertTrue( is_post_status_viewable( 'publish' ) );

		$internal_status = (object) [
			'internal'           => true,
			'protected'          => false,
			'publicly_queryable' => true,
			'_builtin'           => false,
			'public'             => true,
		];
		$this->assertFalse( is_post_status_viewable( $internal_status ) );

		$open_status = (object) [
			'internal'           => false,
			'protected'          => false,
			'publicly_queryable' => true,
			'_builtin'           => false,
			'public'             => false,
		];

		add_filter(
			'is_post_status_viewable',
			static fn( $is_viewable, $post_status ) => '1',
			10,
			2
		);
		$this->assertFalse( is_post_status_viewable( $open_status ) );
	}

	public function test__get_post_mime_types() {
		$mime_types = get_post_mime_types();

		$this->assertArrayHasKey( 'image', $mime_types );
		$this->assertArrayHasKey( 'audio', $mime_types );
		$this->assertArrayHasKey( 'video', $mime_types );
		$this->assertCount( 3, $mime_types['image'] );

		$dynamic_keys = array_filter(
			array_keys( $mime_types ),
			static fn( $key ) => str_contains( $key, '/' ) || str_contains( $key, ',' )
		);
		$this->assertNotEmpty( $dynamic_keys );
	}

	public function test__wp_match_mime_types() {
		$matches = wp_match_mime_types(
			[ 'image/*', 'application/pdf' ],
			[ 'image/jpeg', 'application/pdf', 'text/plain' ]
		);

		$this->assertSame( [ 'image/jpeg' ], $matches['image/*'] );
		$this->assertSame( [ 'application/pdf' ], $matches['application/pdf'] );

		$short_matches = wp_match_mime_types( 'image', 'image/jpeg,text/plain' );
		$this->assertSame( [ 'image/jpeg' ], $short_matches['image'] );
	}

	public function test__wp_post_mime_type_where() {
		$this->assertSame( '', wp_post_mime_type_where( '%' ) );

		$where = wp_post_mime_type_where( [ 'image/*', 'application/pdf' ], 'p' );
		$this->assertStringContainsString( "p.post_mime_type LIKE 'image/%'", $where );
		$this->assertStringContainsString( "p.post_mime_type = 'application/pdf'", $where );
	}

	public function test__wp_resolve_post_date() {
		$this->assertSame( '2024-01-15 10:11:12', wp_resolve_post_date( '2024-01-15 10:11:12' ) );
		$this->assertFalse( wp_resolve_post_date( '2024-02-31 10:11:12' ) );
		$this->assertSame( '2024-01-01 00:00:00', wp_resolve_post_date( '', '2024-01-01 00:00:00' ) );

		$resolved_now = wp_resolve_post_date();
		$this->assertMatchesRegularExpression( '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $resolved_now );
	}

	public function test___truncate_post_slug() {
		$this->assertSame( 'hello-world', _truncate_post_slug( 'hello-world-', 200 ) );
		$this->assertSame( 'abcde', _truncate_post_slug( 'abcdef', 5 ) );

		$encoded   = rawurlencode( 'тестовый_текст' );
		$truncated = _truncate_post_slug( $encoded, 20 );
		$this->assertNotSame( '', $truncated );
		$this->assertLessThanOrEqual( 20, strlen( $truncated ) );
	}

	public function test__get_page_children() {
		$pages = [
			(object) [ 'ID' => 1, 'post_parent' => 0 ],
			(object) [ 'ID' => 2, 'post_parent' => 1 ],
			(object) [ 'ID' => 3, 'post_parent' => 1 ],
			(object) [ 'ID' => 4, 'post_parent' => 2 ],
		];

		$children = get_page_children( 1, $pages );

		$this->assertSame(
			[ 2, 4, 3 ],
			array_map( static fn( $page ) => (int) $page->ID, $children )
		);
		$this->assertSame( [], get_page_children( 999, $pages ) );
	}

	public function test__get_page_hierarchy() {
		$pages = [
			(object) [ 'ID' => 1, 'post_parent' => 0, 'post_name' => 'root' ],
			(object) [ 'ID' => 2, 'post_parent' => 1, 'post_name' => 'child-1' ],
			(object) [ 'ID' => 3, 'post_parent' => 1, 'post_name' => 'child-2' ],
			(object) [ 'ID' => 4, 'post_parent' => 2, 'post_name' => 'grandchild' ],
		];

		$this->assertSame(
			[
				1 => 'root',
				2 => 'child-1',
				4 => 'grandchild',
				3 => 'child-2',
			],
			get_page_hierarchy( $pages )
		);

		$empty_pages = [];
		$this->assertSame( [], get_page_hierarchy( $empty_pages ) );
	}

	public function test___page_traverse_name() {
		$children = [
			0 => [ (object) [ 'ID' => 1, 'post_name' => 'root' ] ],
			1 => [ (object) [ 'ID' => 2, 'post_name' => 'child' ] ],
		];
		$result = [];

		_page_traverse_name( 0, $children, $result );

		$this->assertSame(
			[
				1 => 'root',
				2 => 'child',
			],
			$result
		);
	}

	public function test__wp_untrash_post_set_previous_status() {
		$this->assertSame(
			'draft',
			wp_untrash_post_set_previous_status( 'publish', 12, 'draft' )
		);
	}

	public function test__use_block_editor_for_post_type() {
		$this->assertFalse( use_block_editor_for_post_type( 'missing' ) );

		$GLOBALS['wp_post_types']['book'] = (object) [
			'name'         => 'book',
			'show_in_rest' => true,
		];

		add_post_type_support( 'book', 'custom-fields' );
		$this->assertFalse( use_block_editor_for_post_type( 'book' ) );

		add_post_type_support( 'book', 'editor' );
		$this->assertTrue( use_block_editor_for_post_type( 'book' ) );

		$GLOBALS['wp_post_types']['legacy'] = (object) [
			'name'         => 'legacy',
			'show_in_rest' => false,
		];
		add_post_type_support( 'legacy', 'editor' );
		$this->assertFalse( use_block_editor_for_post_type( 'legacy' ) );

		add_filter(
			'use_block_editor_for_post_type',
			static fn( $use_block_editor, $post_type ) => false,
			10,
			2
		);
		$this->assertFalse( use_block_editor_for_post_type( 'book' ) );
	}
}
