<?php

// ------------------auto-generated---------------------

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'add_filter' ) ) :
	function add_filter( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
		global $wp_filter;
	
		if ( ! isset( $wp_filter[ $hook_name ] ) ) {
			$wp_filter[ $hook_name ] = new WP_Hook();
		}
	
		$wp_filter[ $hook_name ]->add_filter( $hook_name, $callback, $priority, $accepted_args );
	
		return true;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'apply_filters' ) ) :
	function apply_filters( $hook_name, $value, ...$args ) {
		global $wp_filter, $wp_filters, $wp_current_filter;
	
		if ( ! isset( $wp_filters[ $hook_name ] ) ) {
			$wp_filters[ $hook_name ] = 1;
		} else {
			++$wp_filters[ $hook_name ];
		}
	
		// Do 'all' actions first.
		if ( isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
	
			$all_args = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
			_wp_call_all_hook( $all_args );
		}
	
		if ( ! isset( $wp_filter[ $hook_name ] ) ) {
			if ( isset( $wp_filter['all'] ) ) {
				array_pop( $wp_current_filter );
			}
	
			return $value;
		}
	
		if ( ! isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
		}
	
		// Pass the value to WP_Hook.
		array_unshift( $args, $value );
	
		$filtered = $wp_filter[ $hook_name ]->apply_filters( $value, $args );
	
		array_pop( $wp_current_filter );
	
		return $filtered;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'apply_filters_ref_array' ) ) :
	function apply_filters_ref_array( $hook_name, $args ) {
		global $wp_filter, $wp_filters, $wp_current_filter;
	
		if ( ! isset( $wp_filters[ $hook_name ] ) ) {
			$wp_filters[ $hook_name ] = 1;
		} else {
			++$wp_filters[ $hook_name ];
		}
	
		// Do 'all' actions first.
		if ( isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
			$all_args            = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
			_wp_call_all_hook( $all_args );
		}
	
		if ( ! isset( $wp_filter[ $hook_name ] ) ) {
			if ( isset( $wp_filter['all'] ) ) {
				array_pop( $wp_current_filter );
			}
	
			return $args[0];
		}
	
		if ( ! isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
		}
	
		$filtered = $wp_filter[ $hook_name ]->apply_filters( $args[0], $args );
	
		array_pop( $wp_current_filter );
	
		return $filtered;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'has_filter' ) ) :
	function has_filter( $hook_name, $callback = false ) {
		global $wp_filter;
	
		if ( ! isset( $wp_filter[ $hook_name ] ) ) {
			return false;
		}
	
		return $wp_filter[ $hook_name ]->has_filter( $hook_name, $callback );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'remove_filter' ) ) :
	function remove_filter( $hook_name, $callback, $priority = 10 ) {
		global $wp_filter;
	
		$r = false;
	
		if ( isset( $wp_filter[ $hook_name ] ) ) {
			$r = $wp_filter[ $hook_name ]->remove_filter( $hook_name, $callback, $priority );
	
			if ( ! $wp_filter[ $hook_name ]->callbacks ) {
				unset( $wp_filter[ $hook_name ] );
			}
		}
	
		return $r;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'remove_all_filters' ) ) :
	function remove_all_filters( $hook_name, $priority = false ) {
		global $wp_filter;
	
		if ( isset( $wp_filter[ $hook_name ] ) ) {
			$wp_filter[ $hook_name ]->remove_all_filters( $priority );
	
			if ( ! $wp_filter[ $hook_name ]->has_filters() ) {
				unset( $wp_filter[ $hook_name ] );
			}
		}
	
		return true;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'current_filter' ) ) :
	function current_filter() {
		global $wp_current_filter;
	
		return end( $wp_current_filter );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'doing_filter' ) ) :
	function doing_filter( $hook_name = null ) {
		global $wp_current_filter;
	
		if ( null === $hook_name ) {
			return ! empty( $wp_current_filter );
		}
	
		return in_array( $hook_name, $wp_current_filter, true );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'did_filter' ) ) :
	function did_filter( $hook_name ) {
		global $wp_filters;
	
		if ( ! isset( $wp_filters[ $hook_name ] ) ) {
			return 0;
		}
	
		return $wp_filters[ $hook_name ];
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'add_action' ) ) :
	function add_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
		return add_filter( $hook_name, $callback, $priority, $accepted_args );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'do_action' ) ) :
	function do_action( $hook_name, ...$arg ) {
		global $wp_filter, $wp_actions, $wp_current_filter;
	
		if ( ! isset( $wp_actions[ $hook_name ] ) ) {
			$wp_actions[ $hook_name ] = 1;
		} else {
			++$wp_actions[ $hook_name ];
		}
	
		// Do 'all' actions first.
		if ( isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
			$all_args            = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
			_wp_call_all_hook( $all_args );
		}
	
		if ( ! isset( $wp_filter[ $hook_name ] ) ) {
			if ( isset( $wp_filter['all'] ) ) {
				array_pop( $wp_current_filter );
			}
	
			return;
		}
	
		if ( ! isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
		}
	
		if ( empty( $arg ) ) {
			$arg[] = '';
		} elseif ( is_array( $arg[0] ) && 1 === count( $arg[0] ) && isset( $arg[0][0] ) && is_object( $arg[0][0] ) ) {
			// Backward compatibility for PHP4-style passing of `array( &$this )` as action `$arg`.
			$arg[0] = $arg[0][0];
		}
	
		$wp_filter[ $hook_name ]->do_action( $arg );
	
		array_pop( $wp_current_filter );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'do_action_ref_array' ) ) :
	function do_action_ref_array( $hook_name, $args ) {
		global $wp_filter, $wp_actions, $wp_current_filter;
	
		if ( ! isset( $wp_actions[ $hook_name ] ) ) {
			$wp_actions[ $hook_name ] = 1;
		} else {
			++$wp_actions[ $hook_name ];
		}
	
		// Do 'all' actions first.
		if ( isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
			$all_args            = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
			_wp_call_all_hook( $all_args );
		}
	
		if ( ! isset( $wp_filter[ $hook_name ] ) ) {
			if ( isset( $wp_filter['all'] ) ) {
				array_pop( $wp_current_filter );
			}
	
			return;
		}
	
		if ( ! isset( $wp_filter['all'] ) ) {
			$wp_current_filter[] = $hook_name;
		}
	
		$wp_filter[ $hook_name ]->do_action( $args );
	
		array_pop( $wp_current_filter );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'has_action' ) ) :
	function has_action( $hook_name, $callback = false ) {
		return has_filter( $hook_name, $callback );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'remove_action' ) ) :
	function remove_action( $hook_name, $callback, $priority = 10 ) {
		return remove_filter( $hook_name, $callback, $priority );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'remove_all_actions' ) ) :
	function remove_all_actions( $hook_name, $priority = false ) {
		return remove_all_filters( $hook_name, $priority );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'current_action' ) ) :
	function current_action() {
		return current_filter();
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'doing_action' ) ) :
	function doing_action( $hook_name = null ) {
		return doing_filter( $hook_name );
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'did_action' ) ) :
	function did_action( $hook_name ) {
		global $wp_actions;
	
		if ( ! isset( $wp_actions[ $hook_name ] ) ) {
			return 0;
		}
	
		return $wp_actions[ $hook_name ];
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'plugin_basename' ) ) :
	function plugin_basename( $file ) {
		global $wp_plugin_paths;
	
		// $wp_plugin_paths contains normalized paths.
		$file = wp_normalize_path( $file );
	
		arsort( $wp_plugin_paths );
	
		foreach ( $wp_plugin_paths as $dir => $realdir ) {
			if ( str_starts_with( $file, $realdir ) ) {
				$file = $dir . substr( $file, strlen( $realdir ) );
			}
		}
	
		$plugin_dir    = wp_normalize_path( WP_PLUGIN_DIR );
		$mu_plugin_dir = wp_normalize_path( WPMU_PLUGIN_DIR );
	
		// Get relative path from plugins directory.
		$file = preg_replace( '#^' . preg_quote( $plugin_dir, '#' ) . '/|^' . preg_quote( $mu_plugin_dir, '#' ) . '/#', '', $file );
		$file = trim( $file, '/' );
		return $file;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'wp_register_plugin_realpath' ) ) :
	function wp_register_plugin_realpath( $file ) {
		global $wp_plugin_paths;
	
		// Normalize, but store as static to avoid recalculation of a constant value.
		static $wp_plugin_path = null, $wpmu_plugin_path = null;
	
		if ( ! isset( $wp_plugin_path ) ) {
			$wp_plugin_path   = wp_normalize_path( WP_PLUGIN_DIR );
			$wpmu_plugin_path = wp_normalize_path( WPMU_PLUGIN_DIR );
		}
	
		$plugin_path     = wp_normalize_path( dirname( $file ) );
		$plugin_realpath = wp_normalize_path( dirname( realpath( $file ) ) );
	
		if ( $plugin_path === $wp_plugin_path || $plugin_path === $wpmu_plugin_path ) {
			return false;
		}
	
		if ( $plugin_path !== $plugin_realpath ) {
			$wp_plugin_paths[ $plugin_path ] = $plugin_realpath;
		}
	
		return true;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( '_wp_filter_build_unique_id' ) ) :
	function _wp_filter_build_unique_id( $hook_name, $callback, $priority ) {
		if ( is_string( $callback ) ) {
			return $callback;
		}
	
		if ( is_object( $callback ) ) {
			// Closures are currently implemented as objects.
			$callback = array( $callback, '' );
		} else {
			$callback = (array) $callback;
		}
	
		if ( is_object( $callback[0] ) ) {
			// Object class calling.
			return spl_object_hash( $callback[0] ) . $callback[1];
		} elseif ( is_string( $callback[0] ) ) {
			// Static calling.
			return $callback[0] . '::' . $callback[1];
		}
	}
endif;

