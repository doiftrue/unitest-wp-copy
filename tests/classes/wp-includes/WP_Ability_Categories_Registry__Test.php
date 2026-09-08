<?php

class WP_Ability_Categories_Registry__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		if( wp_version_compare( '>= 6.9.0' ) ){
			$this->reset_registry();
			did_action( 'init' ) || do_action( 'init' );
		}
	}

	protected function tearDown(): void {
		if( wp_version_compare( '>= 6.9.0' ) ){
			$this->reset_registry();
		}

		parent::tearDown();
	}

	private function reset_registry(): void {
		$reset = Closure::bind( fn () => WP_Ability_Categories_Registry::$instance = null, null, WP_Ability_Categories_Registry::class );
		$reset();
	}

	public function test__public_methods() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "WP_Ability_Categories_Registry not exists on WP $wp_ver" );
		}

		$registry = WP_Ability_Categories_Registry::get_instance();
		$category = $registry->register( 'content', [
			'label'       => 'Content',
			'description' => 'Content abilities.',
		] );

		$this->assertInstanceOf( WP_Ability_Category::class, $category );
		$this->assertTrue( $registry->is_registered( 'content' ) );
		$this->assertSame( $category, $registry->get_registered( 'content' ) );
		$this->assertSame( [ 'content' => $category ], $registry->get_all_registered() );
		$this->assertSame( $category, $registry->unregister( 'content' ) );
	}
}
