<?php

class Unitest_WP_Copy_Dummy_Widget_For_Factory {
	public string $id_base = 'unitest_dummy_widget';
	public int $registered = 0;

	public function _register(): void {
		++$this->registered;
	}
}

class WP_Widget_Factory__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		global $wp_registered_widgets;

		$wp_registered_widgets = [];
		$factory = new WP_Widget_Factory();

		$factory->register( Unitest_WP_Copy_Dummy_Widget_For_Factory::class );

		$widget = $factory->get_widget_object( 'unitest_dummy_widget' );
		$this->assertInstanceOf( Unitest_WP_Copy_Dummy_Widget_For_Factory::class, $widget );
		$this->assertSame( Unitest_WP_Copy_Dummy_Widget_For_Factory::class, $factory->get_widget_key( 'unitest_dummy_widget' ) );

		$factory->_register_widgets();
		$this->assertSame( 1, $widget->registered );

		$wp_registered_widgets['unitest_dummy_widget-2'] = [];
		$factory->register( Unitest_WP_Copy_Dummy_Widget_For_Factory::class );
		$factory->_register_widgets();
		$this->assertNull( $factory->get_widget_object( 'unitest_dummy_widget' ) );

		$factory->register( Unitest_WP_Copy_Dummy_Widget_For_Factory::class );
		$factory->unregister( Unitest_WP_Copy_Dummy_Widget_For_Factory::class );
		$this->assertSame( '', $factory->get_widget_key( 'unitest_dummy_widget' ) );
	}
}
