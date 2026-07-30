<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class load__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_multisite() {
		$this->assertFalse( is_multisite() );
	}

	public function test__is_multisite__mockable_handler() {
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
		$this->assertTrue( is_multisite() );
	}

	public function test__is_admin() {
		$GLOBALS['current_screen'] = new class {
			public function in_admin() {
				return true;
			}
		};

		$this->assertTrue( is_admin() );
		unset( $GLOBALS['current_screen'] );
		$this->assertFalse( is_admin() );
	}

	public function test__is_admin__mockable_handler() {
		\WP_Mock::userFunction( 'is_admin' )->andReturn( true );
		$this->assertTrue( is_admin() );
	}

	public function test__get_current_blog_id(): void {
		$GLOBALS['blog_id'] = 12;
		$this->assertSame( 12, get_current_blog_id() );
	}

	public function test__get_current_blog_id__mockable_handler(): void {
		\WP_Mock::userFunction( 'get_current_blog_id', [ 'return' => 77 ] );
		$this->assertSame( 77, get_current_blog_id() );
	}

	public function test__get_current_network_id(): void {
		$this->assertSame( 1, get_current_network_id() );
	}

	public function test__get_current_network_id__mockable_handler(): void {
		\WP_Mock::userFunction( 'get_current_network_id', [ 'return' => 88 ] );
		$this->assertSame( 88, get_current_network_id() );
	}

	public function test__timer_float(): void {
		$_SERVER['REQUEST_TIME_FLOAT'] = microtime( true ) - 0.5;
		$this->assertGreaterThan( 0, timer_float() );
	}

	public function test__timer_float__mockable_handler(): void {
		\WP_Mock::userFunction( 'timer_float', [ 'return' => 1.25 ] );
		$this->assertSame( 1.25, timer_float() );
	}

	public function test__timer_stop(): void {
		timer_start();
		$this->assertMatchesRegularExpression( '/^\d+(?:[.,]\d+)?$/', timer_stop( 0, 3 ) );
	}

	public function test__timer_stop__mockable_handler(): void {
		\WP_Mock::userFunction( 'timer_stop', [ 'return' => '2.500' ] );
		$this->assertSame( '2.500', timer_stop() );
	}

	public function test__wp_get_server_protocol(): void {
		$prev = $_SERVER['SERVER_PROTOCOL'] ?? null;
		unset( $_SERVER['SERVER_PROTOCOL'] );
		$this->assertSame( 'HTTP/1.0', wp_get_server_protocol() );
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/2';
		$this->assertSame( 'HTTP/2', wp_get_server_protocol() );
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/9';
		$this->assertSame( 'HTTP/1.0', wp_get_server_protocol() );
		if( null === $prev ){
			unset( $_SERVER['SERVER_PROTOCOL'] );
		} else {
			$_SERVER['SERVER_PROTOCOL'] = $prev;
		}
	}

	public function test__wp_get_server_protocol__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_get_server_protocol', [ 'return' => 'HTTP/3' ] );
		$this->assertSame( 'HTTP/3', wp_get_server_protocol() );
	}

	public function test__wp_doing_cron(): void {
		$this->assertFalse( wp_doing_cron() );
	}

	public function test__wp_doing_cron__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_doing_cron', [ 'return' => true ] );
		$this->assertTrue( wp_doing_cron() );
	}

	public function test__wp_using_themes(): void {
		$this->assertFalse( wp_using_themes() );
	}

	public function test__wp_using_themes__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_using_themes', [ 'return' => true ] );
		$this->assertTrue( wp_using_themes() );
	}

	public function test__wp_is_file_mod_allowed(): void {
		$this->assertTrue( wp_is_file_mod_allowed( 'test' ) );
	}

	public function test__wp_is_file_mod_allowed__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_is_file_mod_allowed', [ 'return' => false ] );
		$this->assertFalse( wp_is_file_mod_allowed( 'test' ) );
	}

	public function test__is_login(): void {
		$script_name = $_SERVER['SCRIPT_NAME'] ?? null;

		$_SERVER['SCRIPT_NAME'] = '/wp-login.php';
		$this->assertTrue( is_login() );

		$_SERVER['SCRIPT_NAME'] = '/index.php';
		$this->assertFalse( is_login() );

		if ( null === $script_name ) {
			unset( $_SERVER['SCRIPT_NAME'] );
		} else {
			$_SERVER['SCRIPT_NAME'] = $script_name;
		}
	}

	public function test__is_login__mockable_handler(): void {
		\WP_Mock::userFunction( 'is_login', [ 'return' => true ] );
		$this->assertTrue( is_login() );
	}

	public function test__wp_is_json_request(): void {
		$accept       = $_SERVER['HTTP_ACCEPT'] ?? null;
		$content_type = $_SERVER['CONTENT_TYPE'] ?? null;

		unset( $_SERVER['HTTP_ACCEPT'], $_SERVER['CONTENT_TYPE'] );
		$this->assertFalse( wp_is_json_request() );

		$_SERVER['HTTP_ACCEPT'] = 'text/html, application/json';
		$this->assertTrue( wp_is_json_request() );

		unset( $_SERVER['HTTP_ACCEPT'] );
		$_SERVER['CONTENT_TYPE'] = 'application/activity+json';
		$this->assertTrue( wp_is_json_request() );

		if ( null === $accept ) {
			unset( $_SERVER['HTTP_ACCEPT'] );
		} else {
			$_SERVER['HTTP_ACCEPT'] = $accept;
		}
		if ( null === $content_type ) {
			unset( $_SERVER['CONTENT_TYPE'] );
		} else {
			$_SERVER['CONTENT_TYPE'] = $content_type;
		}
	}

	public function test__wp_is_json_request__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_is_json_request', [ 'return' => true ] );
		$this->assertTrue( wp_is_json_request() );
	}

	public function test__wp_is_jsonp_request(): void {
		$jsonp = $_GET['_jsonp'] ?? null;

		unset( $_GET['_jsonp'] );
		$this->assertFalse( wp_is_jsonp_request() );

		$_GET['_jsonp'] = 'unitestCallback';
		$this->assertTrue( wp_is_jsonp_request() );

		$_GET['_jsonp'] = 'invalid callback';
		$this->assertFalse( wp_is_jsonp_request() );

		if ( null === $jsonp ) {
			unset( $_GET['_jsonp'] );
		} else {
			$_GET['_jsonp'] = $jsonp;
		}
	}

	public function test__wp_is_jsonp_request__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_is_jsonp_request', [ 'return' => true ] );
		$this->assertTrue( wp_is_jsonp_request() );
	}

	public function test__wp_is_xml_request(): void {
		$accept       = $_SERVER['HTTP_ACCEPT'] ?? null;
		$content_type = $_SERVER['CONTENT_TYPE'] ?? null;

		unset( $_SERVER['HTTP_ACCEPT'], $_SERVER['CONTENT_TYPE'] );
		$this->assertFalse( wp_is_xml_request() );

		$_SERVER['HTTP_ACCEPT'] = 'text/html, application/rss+xml';
		$this->assertTrue( wp_is_xml_request() );

		unset( $_SERVER['HTTP_ACCEPT'] );
		$_SERVER['CONTENT_TYPE'] = 'application/atom+xml';
		$this->assertTrue( wp_is_xml_request() );

		if ( null === $accept ) {
			unset( $_SERVER['HTTP_ACCEPT'] );
		} else {
			$_SERVER['HTTP_ACCEPT'] = $accept;
		}
		if ( null === $content_type ) {
			unset( $_SERVER['CONTENT_TYPE'] );
		} else {
			$_SERVER['CONTENT_TYPE'] = $content_type;
		}
	}

	public function test__wp_is_xml_request__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_is_xml_request', [ 'return' => true ] );
		$this->assertTrue( wp_is_xml_request() );
	}

	public function test__is_blog_admin(): void {
		$GLOBALS['current_screen'] = new class {
			public function in_admin( $context = null ) {
				return 'site' === $context;
			}
		};
		$this->assertTrue( is_blog_admin() );
		unset( $GLOBALS['current_screen'] );
		$this->assertFalse( is_blog_admin() );
	}

	public function test__is_blog_admin__mockable_handler(): void {
		\WP_Mock::userFunction( 'is_blog_admin', [ 'return' => true ] );
		$this->assertTrue( is_blog_admin() );
	}

	public function test__is_network_admin(): void {
		$GLOBALS['current_screen'] = new class {
			public function in_admin( $context = null ) {
				return 'network' === $context;
			}
		};
		$this->assertTrue( is_network_admin() );
		unset( $GLOBALS['current_screen'] );
		$this->assertFalse( is_network_admin() );
	}

	public function test__is_network_admin__mockable_handler(): void {
		\WP_Mock::userFunction( 'is_network_admin', [ 'return' => true ] );
		$this->assertTrue( is_network_admin() );
	}

	public function test__is_user_admin(): void {
		$GLOBALS['current_screen'] = new class {
			public function in_admin( $context = null ) {
				return 'user' === $context;
			}
		};
		$this->assertTrue( is_user_admin() );
		unset( $GLOBALS['current_screen'] );
		$this->assertFalse( is_user_admin() );
	}

	public function test__is_user_admin__mockable_handler(): void {
		\WP_Mock::userFunction( 'is_user_admin', [ 'return' => true ] );
		$this->assertTrue( is_user_admin() );
	}

}
