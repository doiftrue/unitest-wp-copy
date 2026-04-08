<?php

if ( ! class_exists( 'Walker_For_Test' ) ) {
	class Walker_For_Test extends Walker {
		public $db_fields = [ 'parent' => 'parent', 'id' => 'id' ];

		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= '[L' . $depth . ']';
		}

		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= '[/L' . $depth . ']';
		}

		public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
			$output .= '[E' . $data_object->id . ']';
		}

		public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {
			$output .= '[/E' . $data_object->id . ']';
		}
	}
}

class Walker__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$walker = new Walker_For_Test();
		$e1 = (object) [ 'id' => 1, 'parent' => 0 ];
		$e2 = (object) [ 'id' => 2, 'parent' => 1 ];
		$e3 = (object) [ 'id' => 3, 'parent' => 0 ];
		$elements = [ $e1, $e2, $e3 ];

		$out = '';
		$children = [ 1 => [ $e2 ] ];
		$walker->display_element( $e1, $children, 0, 0, [], $out );
		$this->assertNotEmpty( $out );

		$walk = $walker->walk( $elements, 0 );
		$this->assertStringContainsString( '[E1]', $walk );

		$paged = $walker->paged_walk( $elements, 1, 1, 1 );
		$this->assertIsString( $paged );

		$this->assertSame( 2, $walker->get_number_of_root_elements( $elements ) );

		$children2 = [ 1 => [ $e2 ] ];
		$walker->unset_children( $e1, $children2 );
		$this->assertArrayNotHasKey( 1, $children2 );
	}
}

