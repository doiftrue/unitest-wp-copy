<?php

class WP_Abilities_Registry__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		if( wp_version_compare( '>= 6.9.0' ) ){
			$this->reset_registry( WP_Abilities_Registry::class );
			$this->reset_registry( WP_Ability_Categories_Registry::class );

			did_action( 'init' ) || do_action( 'init' );

			WP_Ability_Categories_Registry::get_instance()->register( 'content', [
				'label'       => 'Content',
				'description' => 'Content abilities.',
			] );
		}
	}

	protected function tearDown(): void {
		if( wp_version_compare( '>= 6.9.0' ) ){
			$this->reset_registry( WP_Abilities_Registry::class );
			$this->reset_registry( WP_Ability_Categories_Registry::class );
		}

		parent::tearDown();
	}

	private function reset_registry( string $class_name ): void {
		$reset = Closure::bind( static fn () => static::$instance = null, null, $class_name );
		$reset();
	}

	public function test__public_methods(): void {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "WP_Abilities_Registry not exists on WP $wp_ver" );
		}

		$registry = WP_Abilities_Registry::get_instance();
		$ability = $registry->register( 'unitest/run', [
			'label'               => 'Run',
			'description'         => 'Runs a callback.',
			'category'            => 'content',
			'execute_callback'    => static fn() => 'done',
			'permission_callback' => static fn() => true,
		] );

		$this->assertInstanceOf( WP_Ability::class, $ability );
		$this->assertTrue( $registry->is_registered( 'unitest/run' ) );
		$this->assertSame( $ability, $registry->get_registered( 'unitest/run' ) );
		$this->assertSame( [ 'unitest/run' => $ability ], $registry->get_all_registered() );
		$this->assertSame( $ability, $registry->unregister( 'unitest/run' ) );
	}
}
