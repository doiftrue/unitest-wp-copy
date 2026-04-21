<?php

class WP_Sitemaps_Provider_Test_Rewrite {

	public bool $permalinks = true;

	public function using_permalinks() {
		return $this->permalinks;
	}
}

class WP_Sitemaps_Provider_Test_Demo extends WP_Sitemaps_Provider {

	private array $subtype_pages;
	private int $default_pages;

	public function __construct( array $subtype_pages = [], int $default_pages = 1 ) {
		$this->name          = 'demo';
		$this->object_type   = 'demo-object';
		$this->subtype_pages = $subtype_pages;
		$this->default_pages = $default_pages;
	}

	public function get_url_list( $page_num, $object_subtype = '' ) {
		return [];
	}

	public function get_max_num_pages( $object_subtype = '' ) {
		if ( '' === $object_subtype ) {
			return $this->default_pages;
		}

		return $this->subtype_pages[ $object_subtype ] ?? 0;
	}

	public function get_object_subtypes() {
		return array_fill_keys( array_keys( $this->subtype_pages ), (object) [] );
	}
}

class WP_Sitemaps_Provider__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_rewrite']        = new WP_Sitemaps_Provider_Test_Rewrite();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['wp_rewrite'] );

		parent::tearDown();
	}

	public function test__get_sitemap_type_data() {
		$provider_without_subtypes = new WP_Sitemaps_Provider_Test_Demo( [], 2 );
		$this->assertSame(
			[
				[
					'name'  => '',
					'pages' => 2,
				],
			],
			$provider_without_subtypes->get_sitemap_type_data()
		);

		$provider_with_subtypes = new WP_Sitemaps_Provider_Test_Demo(
			[
				'post' => 2,
				'page' => 1,
			]
		);

		$this->assertSame(
			[
				[
					'name'  => 'post',
					'pages' => 2,
				],
				[
					'name'  => 'page',
					'pages' => 1,
				],
			],
			$provider_with_subtypes->get_sitemap_type_data()
		);
	}

	public function test__get_sitemap_entries() {
		add_filter(
			'wp_sitemaps_index_entry',
			static function ( $entry, $object_type, $object_subtype, $page ) {
				$entry['page']          = $page;
				$entry['object_type']   = $object_type;
				$entry['object_subtype'] = $object_subtype;
				return $entry;
			},
			10,
			4
		);

		$provider = new WP_Sitemaps_Provider_Test_Demo(
			[
				'post' => 2,
			]
		);

		$entries = $provider->get_sitemap_entries();

		$this->assertCount( 2, $entries );
		$this->assertStringContainsString( '/wp-sitemap-demo-post-1.xml', $entries[0]['loc'] );
		$this->assertSame( 1, $entries[0]['page'] );
		$this->assertSame( 'demo-object', $entries[0]['object_type'] );
		$this->assertSame( 'post', $entries[0]['object_subtype'] );
	}

	public function test__get_sitemap_url() {
		$provider = new WP_Sitemaps_Provider_Test_Demo();

		$url = $provider->get_sitemap_url( 'post', 3 );
		$this->assertStringContainsString( '/wp-sitemap-demo-post-3.xml', $url );

		$GLOBALS['wp_rewrite']->permalinks = false;

		$query_url = $provider->get_sitemap_url( 'post', 3 );
		$this->assertStringContainsString( '?sitemap=demo&sitemap-subtype=post&paged=3', $query_url );
	}
}
