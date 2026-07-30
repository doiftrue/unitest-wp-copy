<?php

class WP_Style_Engine_CSS_Rules_Store__Test extends \PHPUnit\Framework\TestCase {

	protected function tearDown(): void {
		WP_Style_Engine_CSS_Rules_Store::remove_all_stores();
	}

	public function test__public_methods() {
		$store = WP_Style_Engine_CSS_Rules_Store::get_store( 'test' );
		$rule = $store->add_rule( '.notice' );
		$rule->add_declarations( [ 'color' => 'red' ] );

		$this->assertSame( 'test', $store->get_name() );
		$this->assertSame( [ '.notice' => $rule ], $store->get_all_rules() );
	}
}
