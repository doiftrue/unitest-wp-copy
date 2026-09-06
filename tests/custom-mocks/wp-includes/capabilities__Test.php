<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class capabilities__custom_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__current_user_can(): void {
		$this->assertFalse( current_user_can( 'edit_posts' ) );

		\WP_Mock::userFunction( 'current_user_can' )
			->with( 'edit_post', 15 )
			->andReturn( true );

		$this->assertTrue( current_user_can( 'edit_post', 15 ) );

		\WP_Mock::userFunction( 'current_user_can' )
		        ->andReturn( false )->byDefault();

		$this->assertFalse( current_user_can( 'foo' ) );
	}

}
