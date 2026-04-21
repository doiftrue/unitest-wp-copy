<?php

class WP_Sitemaps_Registry_Test_Provider extends WP_Sitemaps_Provider {

	public function __construct() {
		$this->name        = 'demo';
		$this->object_type = 'demo';
	}

	public function get_url_list( $page_num, $object_subtype = '' ) {
		return [];
	}

	public function get_max_num_pages( $object_subtype = '' ) {
		return 0;
	}
}

class WP_Sitemaps_Registry__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	public function test__public_methods() {
		$registry = new WP_Sitemaps_Registry();
		$provider = new WP_Sitemaps_Registry_Test_Provider();

		$this->assertTrue( $registry->add_provider( 'demo', $provider ) );
		$this->assertFalse( $registry->add_provider( 'demo', new WP_Sitemaps_Registry_Test_Provider() ) );
		$this->assertSame( $provider, $registry->get_provider( 'demo' ) );
		$this->assertNull( $registry->get_provider( 'missing' ) );
		$this->assertSame( [ 'demo' => $provider ], $registry->get_providers() );
	}

	public function test__add_provider_filter_can_reject_provider() {
		$registry = new WP_Sitemaps_Registry();

		add_filter( 'wp_sitemaps_add_provider', static fn() => null, 10, 2 );

		$this->assertFalse( $registry->add_provider( 'demo', new WP_Sitemaps_Registry_Test_Provider() ) );
		$this->assertNull( $registry->get_provider( 'demo' ) );
	}
}
