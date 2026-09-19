<?php

// ------------------auto-generated---------------------

// wp-includes/cron.php (WP 7.0.5)
if( ! function_exists( 'wp_get_schedules' ) ) :
	function wp_get_schedules() {
		$schedules = array(
			'hourly'     => array(
				'interval' => HOUR_IN_SECONDS,
				'display'  => __( 'Once Hourly' ),
			),
			'twicedaily' => array(
				'interval' => 12 * HOUR_IN_SECONDS,
				'display'  => __( 'Twice Daily' ),
			),
			'daily'      => array(
				'interval' => DAY_IN_SECONDS,
				'display'  => __( 'Once Daily' ),
			),
			'weekly'     => array(
				'interval' => WEEK_IN_SECONDS,
				'display'  => __( 'Once Weekly' ),
			),
		);
	
		/**
		 * Filters the non-default cron schedules.
		 *
		 * @since 2.1.0
		 *
		 * @param array $new_schedules {
		 *     An array of non-default cron schedules keyed by the schedule name. Default empty array.
		 *
		 *     @type array ...$0 {
		 *         Cron schedule information.
		 *
		 *         @type int    $interval The schedule interval in seconds.
		 *         @type string $display  The schedule display name.
		 *     }
		 * }
		 */
		return array_merge( apply_filters( 'cron_schedules', array() ), $schedules );
	}
endif;

