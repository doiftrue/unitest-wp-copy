<?php

class WP_Block_Templates_Registry__Test extends \PHPUnit\Framework\TestCase {

	protected function tearDown(): void {
		$reset = Closure::bind( fn () => WP_Block_Templates_Registry::$instance = null, null, WP_Block_Templates_Registry::class );
		$reset();

		parent::tearDown();
	}

	public function test__public_methods() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "WP_Block_Templates_Registry not exists on WP $wp_ver" );
		}

		$registry = WP_Block_Templates_Registry::get_instance();
		$template = $registry->register( 'unitest//landing', [
			'title'      => 'Landing',
			'content'    => '<!-- wp:paragraph /-->',
			'post_types' => [ 'page' ],
		] );

		$this->assertInstanceOf( WP_Block_Template::class, $template );
		$this->assertSame( $template, $registry->get_registered( 'unitest//landing' ) );
		$this->assertSame( $template, $registry->get_by_slug( 'landing' ) );
		$this->assertSame( [ 'unitest//landing' => $template ], $registry->get_by_query( [ 'post_type' => 'page' ] ) );
		$this->assertTrue( $registry->is_registered( 'unitest//landing' ) );
		$this->assertSame( [ 'unitest//landing' => $template ], $registry->get_all_registered() );
		$this->assertSame( $template, $registry->unregister( 'unitest//landing' ) );
	}

}
