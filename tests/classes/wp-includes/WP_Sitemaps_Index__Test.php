<?php

class WP_Sitemaps_Index_Test_Rewrite {

	public bool $permalinks = true;

	public function using_permalinks() {
		return $this->permalinks;
	}
}

class WP_Sitemaps_Index_Test_Provider extends WP_Sitemaps_Provider {

	private array $entries;

	public function __construct( array $entries ) {
		$this->name        = 'demo';
		$this->object_type = 'demo';
		$this->entries     = $entries;
	}

	public function get_url_list( $page_num, $object_subtype = '' ) {
		return [];
	}

	public function get_max_num_pages( $object_subtype = '' ) {
		return 0;
	}

	public function get_sitemap_entries() {
		return $this->entries;
	}
}

class WP_Sitemaps_Index__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['wp_rewrite'] = new WP_Sitemaps_Index_Test_Rewrite();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['wp_rewrite'] );
		parent::tearDown();
	}

	public function test__get_sitemap_list() {
		$registry = new WP_Sitemaps_Registry();
		$registry->add_provider(
			'posts',
			new WP_Sitemaps_Index_Test_Provider(
				[
					[ 'loc' => 'https://unitest-wp-copy.loc/wp-sitemap-posts-1.xml' ],
				]
			)
		);
		$registry->add_provider(
			'pages',
			new WP_Sitemaps_Index_Test_Provider(
				[
					[ 'loc' => 'https://unitest-wp-copy.loc/wp-sitemap-pages-1.xml' ],
					[ 'loc' => 'https://unitest-wp-copy.loc/wp-sitemap-pages-2.xml' ],
				]
			)
		);

		$index = new WP_Sitemaps_Index( $registry );
		$list  = $index->get_sitemap_list();

		$this->assertCount( 3, $list );
		$this->assertSame( 'https://unitest-wp-copy.loc/wp-sitemap-posts-1.xml', $list[0]['loc'] );
		$this->assertSame( 'https://unitest-wp-copy.loc/wp-sitemap-pages-2.xml', $list[2]['loc'] );
	}

	public function test__get_index_url() {
		$index = new WP_Sitemaps_Index( new WP_Sitemaps_Registry() );

		$this->assertStringContainsString( '/wp-sitemap.xml', $index->get_index_url() );

		$GLOBALS['wp_rewrite']->permalinks = false;
		$this->assertStringContainsString( '?sitemap=index', $index->get_index_url() );
	}
}
