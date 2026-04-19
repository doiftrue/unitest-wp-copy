<?php

// ------------------auto-generated---------------------

// wp-admin/includes/template.php (WP 6.6.5)
if( ! function_exists( 'convert_to_screen' ) ) :
	function convert_to_screen( $hook_name ) {
		if ( ! class_exists( 'WP_Screen' ) ) {
			_doing_it_wrong(
				'convert_to_screen(), add_meta_box()',
				sprintf(
					/* translators: 1: wp-admin/includes/template.php, 2: add_meta_box(), 3: add_meta_boxes */
					__( 'Likely direct inclusion of %1$s in order to use %2$s. This is very wrong. Hook the %2$s call into the %3$s action instead.' ),
					'<code>wp-admin/includes/template.php</code>',
					'<code>add_meta_box()</code>',
					'<code>add_meta_boxes</code>'
				),
				'3.3.0'
			);
			return (object) array(
				'id'   => '_invalid',
				'base' => '_are_belong_to_us',
			);
		}
	
		return WP_Screen::get( $hook_name );
	}
endif;

