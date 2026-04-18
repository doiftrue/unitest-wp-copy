<?php

class script_modules_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		unset( $GLOBALS['wp_script_modules'] );
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	public function test__wp_script_modules() {
		$instance_1 = wp_script_modules();
		$instance_2 = wp_script_modules();

		$this->assertInstanceOf( WP_Script_Modules::class, $instance_1 );
		$this->assertSame( $instance_1, $instance_2 );
	}

	public function test__wp_register_script_module() {
		wp_register_script_module( '@test/module', 'https://example.com/module.js' );

		$registered = (array) ( fn() => $this->registered )->call( wp_script_modules() ); // access private;

		$this->assertArrayHasKey( '@test/module', $registered );

		if( wp_version_compare( '>= 6.9.0' ) ){
			$queue = (array) ( fn()=> $this->queue )->call( wp_script_modules() ); // access private
			$this->assertNotContains( '@test/enqueued', $queue );
		} else {
			$this->assertFalse( $registered['@test/module']['enqueue'] );
		}
	}

	public function test__wp_enqueue_script_module() {
		wp_enqueue_script_module( '@test/enqueued', 'https://example.com/enqueued.js' );

		$registered = (array) ( fn() => $this->registered )->call( wp_script_modules() ); // access private

		$this->assertArrayHasKey( '@test/enqueued', $registered );

		if( wp_version_compare( '>= 6.9.0' ) ){
			$queue = (array) ( fn()=> $this->queue )->call( wp_script_modules() ); // access private
			$this->assertContains( '@test/enqueued', $queue );
		} else {
			$this->assertTrue( $registered['@test/enqueued']['enqueue'] );
		}
	}

	public function test__wp_dequeue_script_module() {
		wp_enqueue_script_module( '@test/dequeue', 'https://example.com/dequeue.js' );
		wp_dequeue_script_module( '@test/dequeue' );

		$registered = (array) ( fn() => $this->registered )->call( wp_script_modules() ); // access private

		$this->assertArrayHasKey( '@test/dequeue', $registered );

		if( wp_version_compare( '>= 6.9.0' ) ){
			$queue = (array) ( fn()=> $this->queue )->call( wp_script_modules() ); // access private
			$this->assertNotContains( '@test/dequeue', $queue );
		} else {
			$this->assertFalse( $registered['@test/dequeue']['enqueue'] );
		}
	}

	public function test__wp_deregister_script_module() {
		wp_register_script_module( '@test/remove', 'https://example.com/remove.js' );
		wp_deregister_script_module( '@test/remove' );

		$registered = (array) ( fn() => $this->registered )->call( wp_script_modules() ); // access private;

		$this->assertArrayNotHasKey( '@test/remove', $registered );
	}

}
