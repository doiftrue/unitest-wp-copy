<?php

class block_patterns__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->reset_registry();
	}

	protected function tearDown(): void {
		$this->reset_registry();
		parent::tearDown();
	}

	private function reset_registry(): void {
		$reset = Closure::bind(
			static function () {
				self::$instance = null;
			},
			null,
			WP_Block_Pattern_Categories_Registry::class
		);
		$reset();
	}

	public function test__register_block_pattern_category() {
		$this->assertTrue(
			register_block_pattern_category( 'unitest', [ 'label' => 'Unitest' ] )
		);
		$this->assertTrue(
			WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( 'unitest' )
		);
	}

	public function test__unregister_block_pattern_category() {
		WP_Block_Pattern_Categories_Registry::get_instance()->register( 'unitest', [ 'label' => 'Unitest' ] );

		$this->assertTrue( unregister_block_pattern_category( 'unitest' ) );
		$this->assertFalse(
			WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( 'unitest' )
		);
	}
}
