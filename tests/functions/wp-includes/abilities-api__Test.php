<?php

class abilities_api__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		if( wp_version_compare( '>= 6.9.0' ) ){
			$this->reset_registry( WP_Abilities_Registry::class );
			$this->reset_registry( WP_Ability_Categories_Registry::class );

			did_action( 'init' ) || do_action( 'init' );
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
		$reset = Closure::bind( fn () => static::$instance = null, null, $class_name );
		$reset();
	}

	private function register_category(): WP_Ability_Category {
		$category = null;
		$callback = static function () use ( &$category ) {
			$category = wp_register_ability_category( 'content', [
				'label'       => 'Content',
				'description' => 'Content abilities.',
			] );
		};

		add_action( 'wp_abilities_api_categories_init', $callback );
		WP_Ability_Categories_Registry::get_instance();
		remove_action( 'wp_abilities_api_categories_init', $callback );

		return $category;
	}

	private function register_ability(): WP_Ability {
		$this->register_category();

		$ability = null;
		$callback = static function () use ( &$ability ) {
			$ability = wp_register_ability( 'unitest/run', [
				'label'               => 'Run',
				'description'         => 'Runs a callback.',
				'category'            => 'content',
				'execute_callback'    => static fn() => 'done',
				'permission_callback' => static fn() => true,
				'meta'                => [ 'public' => true, 'channel' => [ 'rest' => true ] ],
			] );
		};

		add_action( 'wp_abilities_api_init', $callback );
		WP_Abilities_Registry::get_instance();
		remove_action( 'wp_abilities_api_init', $callback );

		return $ability;
	}

	public function test__wp_register_ability(): void {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_register_ability() not exists on WP $wp_ver" );
		}

		$this->assertInstanceOf( WP_Ability::class, $this->register_ability() );
	}

	public function test__wp_unregister_ability(): void {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_unregister_ability() not exists on WP $wp_ver" );
		}

		$ability = $this->register_ability();
		$this->assertSame( $ability, wp_unregister_ability( 'unitest/run' ) );
	}

	public function test__wp_has_ability() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_has_ability() not exists on WP $wp_ver" );
		}

		$this->register_ability();
		$this->assertTrue( wp_has_ability( 'unitest/run' ) );
	}

	public function test__wp_get_ability() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_get_ability() not exists on WP $wp_ver" );
		}

		$ability = $this->register_ability();
		$this->assertSame( $ability, wp_get_ability( 'unitest/run' ) );
	}

	public function test__wp_get_abilities() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_get_abilities() not exists on WP $wp_ver" );
		}

		$ability = $this->register_ability();
		$this->assertSame( [ 'unitest/run' => $ability ], wp_get_abilities() );

		if( wp_version_compare( '>= 7.1.0' ) ){
			$this->assertSame(
				[ 'unitest/run' => $ability ],
				wp_get_abilities( [ 'meta' => [ 'channel' => [ 'rest' => true ] ] ] )
			);
		}
	}

	public function test___wp_get_abilities_match_meta() {
		if( $wp_ver = wp_version_compare( '< 7.1.0' ) ){
			$this->markTestSkipped( "_wp_get_abilities_match_meta() not exists on WP $wp_ver" );
		}

		$this->assertTrue( _wp_get_abilities_match_meta(
			[ 'channel' => [ 'rest' => true ] ],
			[ 'channel' => [ 'rest' => true ] ]
		) );
		$this->assertFalse( _wp_get_abilities_match_meta(
			[ 'channel' => [ 'rest' => false ] ],
			[ 'channel' => [ 'rest' => true ] ]
		) );
	}

	public function test__wp_register_ability_category() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_register_ability_category() not exists on WP $wp_ver" );
		}

		$this->assertInstanceOf( WP_Ability_Category::class, $this->register_category() );
	}

	public function test__wp_unregister_ability_category() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_unregister_ability_category() not exists on WP $wp_ver" );
		}

		$category = $this->register_category();
		$this->assertSame( $category, wp_unregister_ability_category( 'content' ) );
	}

	public function test__wp_has_ability_category() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_has_ability_category() not exists on WP $wp_ver" );
		}

		$this->register_category();
		$this->assertTrue( wp_has_ability_category( 'content' ) );
	}

	public function test__wp_get_ability_category() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_get_ability_category() not exists on WP $wp_ver" );
		}

		$category = $this->register_category();
		$this->assertSame( $category, wp_get_ability_category( 'content' ) );
	}

	public function test__wp_get_ability_categories() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_get_ability_categories() not exists on WP $wp_ver" );
		}

		$category = $this->register_category();
		$this->assertSame( [ 'content' => $category ], wp_get_ability_categories() );
	}

}
