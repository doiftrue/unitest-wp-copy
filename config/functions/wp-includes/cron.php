<?php
return [];
/*
Not suitable in isolated PHPUnit env:

wp_schedule_single_event // why: depends on _get_cron_array()/_set_cron_array() persistent option chain
wp_schedule_event        // why: depends on _get_cron_array()/_set_cron_array() persistent option chain
wp_reschedule_event      // why: depends on wp_schedule_event() persistent option chain
wp_unschedule_event      // why: depends on _get_cron_array()/_set_cron_array() persistent option chain
wp_clear_scheduled_hook  // why: depends on _get_cron_array()/_set_cron_array() persistent option chain
wp_unschedule_hook       // why: depends on _get_cron_array()/_set_cron_array() persistent option chain
wp_get_scheduled_event   // why: depends on _get_cron_array() persistent cron option
wp_next_scheduled        // why: depends on wp_get_scheduled_event()
spawn_cron               // why: performs remote HTTP/XML-RPC I/O
wp_cron                  // why: depends on _wp_cron()
_wp_cron                 // why: depends on wp_get_ready_cron_jobs()
wp_get_schedules         // why: exposes cron lifecycle state through hooks
wp_get_schedule          // why: depends on wp_get_scheduled_event()
wp_get_ready_cron_jobs   // why: depends on _get_cron_array()
_get_cron_array          // why: reads unresolved option `cron`
_set_cron_array          // why: mutates persistent option `cron`
_upgrade_cron_array      // why: mutates persistent option `cron`

*/
