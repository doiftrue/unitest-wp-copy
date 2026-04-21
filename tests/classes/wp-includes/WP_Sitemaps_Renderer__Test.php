<?php

class WP_Sitemaps_Renderer_Test_Rewrite {

	public bool $permalinks = true;

	public function using_permalinks() {
		return $this->permalinks;
	}
}

class WP_Sitemaps_Renderer__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_rewrite']        = new WP_Sitemaps_Renderer_Test_Rewrite();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['wp_rewrite'] );

		parent::tearDown();
	}

	public function test__stylesheet_urls_for_permalinks_modes() {
		$renderer = new WP_Sitemaps_Renderer();

		$this->assertStringContainsString( '/wp-sitemap.xsl', $renderer->get_sitemap_stylesheet_url() );
		$this->assertStringContainsString( '/wp-sitemap-index.xsl', $renderer->get_sitemap_index_stylesheet_url() );

		$GLOBALS['wp_rewrite']->permalinks = false;

		$this->assertStringContainsString( '?sitemap-stylesheet=sitemap', $renderer->get_sitemap_stylesheet_url() );
		$this->assertStringContainsString( '?sitemap-stylesheet=index', $renderer->get_sitemap_index_stylesheet_url() );
	}

	public function test__get_sitemap_xml_and_index_xml() {
		add_filter( 'wp_sitemaps_stylesheet_url', '__return_false' );
		add_filter( 'wp_sitemaps_stylesheet_index_url', '__return_false' );

		$renderer = new WP_Sitemaps_Renderer();

		$sitemap_xml = $renderer->get_sitemap_xml( [
			[
				'loc'        => 'https://example.com/post-1',
				'lastmod'    => '2025-01-01T00:00:00+00:00',
				'changefreq' => 'daily',
				'priority'   => '0.8',
			],
		] );

		$index_xml = $renderer->get_sitemap_index_xml( [
			[
				'loc'     => 'https://example.com/wp-sitemap-posts-1.xml',
				'lastmod' => '2025-01-01T00:00:00+00:00',
			],
		] );

		$this->assertStringContainsString( '<urlset', $sitemap_xml );
		$this->assertStringContainsString( '<loc>https://example.com/post-1</loc>', $sitemap_xml );

		$this->assertStringContainsString( '<sitemapindex', $index_xml );
		$this->assertStringContainsString( '<loc>https://example.com/wp-sitemap-posts-1.xml</loc>', $index_xml );
	}
}
