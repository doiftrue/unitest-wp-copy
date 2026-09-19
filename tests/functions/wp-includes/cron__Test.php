<?php

class cron__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_get_schedules(): void {
		$schedules = wp_get_schedules();
		$this->assertSame( HOUR_IN_SECONDS, $schedules['hourly']['interval'] );
		$this->assertSame( WEEK_IN_SECONDS, $schedules['weekly']['interval'] );
	}
}
