<?php

// ------------------auto-generated---------------------

// wp-includes/ms-network.php (WP 6.5.8)
if( ! function_exists( 'clean_network_cache' ) ) :
	function clean_network_cache( $ids ) {
		global $_wp_suspend_cache_invalidation;
	
		if ( ! empty( $_wp_suspend_cache_invalidation ) ) {
			return;
		}
	
		$network_ids = (array) $ids;
		wp_cache_delete_multiple( $network_ids, 'networks' );
	
		foreach ( $network_ids as $id ) {
			/**
			 * Fires immediately after a network has been removed from the object cache.
			 *
			 * @since 4.6.0
			 *
			 * @param int $id Network ID.
			 */
			do_action( 'clean_network_cache', $id );
		}
	
		wp_cache_set_last_changed( 'networks' );
	}
endif;

// wp-includes/ms-network.php (WP 6.5.8)
if( ! function_exists( 'update_network_cache' ) ) :
	function update_network_cache( $networks ) {
		$data = array();
		foreach ( (array) $networks as $network ) {
			$data[ $network->id ] = $network;
		}
		wp_cache_add_multiple( $data, 'networks' );
	}
endif;

