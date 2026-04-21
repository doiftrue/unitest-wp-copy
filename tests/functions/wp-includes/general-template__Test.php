<?php

class general_template__Test extends \PHPUnit\Framework\TestCase {

	private object $initial_stub_wp_options;
	private array $initial_server = [];
	private array $initial_allowedtags = [];

	protected function setUp(): void {
		parent::setUp();

		$this->initial_stub_wp_options = clone $GLOBALS['stub_wp_options'];
		$this->initial_server = $_SERVER;
		$this->initial_allowedtags = $GLOBALS['allowedtags'] ?? [];

		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];

		$_SERVER['REQUEST_URI'] = '/current-uri/';
		$_SERVER['SERVER_NAME'] = 'unitest-wp-copy.loc';
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options'] = clone $this->initial_stub_wp_options;
		$GLOBALS['allowedtags'] = $this->initial_allowedtags;
		$_SERVER = $this->initial_server;

		parent::tearDown();
	}

	public function test__wp_login_url() {
		$url = wp_login_url( 'https://example.test/back', true );

		$this->assertStringContainsString( 'wp-login.php', $url );
		$this->assertStringContainsString( 'redirect_to=https%3A%2F%2Fexample.test%2Fback', $url );
		$this->assertStringContainsString( 'reauth=1', $url );
	}

	public function test__wp_registration_url() {
		$this->assertSame(
			site_url( 'wp-login.php?action=register', 'login' ),
			wp_registration_url()
		);
	}

	public function test__wp_login_form() {
		$form = wp_login_form(
			[
				'echo' => false,
				'redirect' => 'https://example.test/next',
				'required_username' => true,
				'required_password' => true,
				'remember' => false,
			]
		);

		$this->assertStringContainsString( '<form', $form );
		$this->assertStringContainsString( 'required="required"', $form );
		$this->assertStringContainsString( 'name="redirect_to" value="https://example.test/next"', $form );
		$this->assertStringNotContainsString( 'login-remember', $form );
	}

	public function test__wp_meta() {
		$was_called = false;
		add_action(
			'wp_meta',
			static function () use ( &$was_called ) {
				$was_called = true;
			}
		);

		wp_meta();
		$this->assertTrue( $was_called );
	}

	public function test__bloginfo() {
		$GLOBALS['stub_wp_options']->blogdescription = 'Example tagline';

		ob_start();
		bloginfo( 'description' );
		$output = ob_get_clean();

		$this->assertSame( 'Example tagline', $output );
	}

	public function test__get_archives_link() {
		$link = get_archives_link( 'https://example.test/2026/04', 'April 2026', 'html', '', '', true );

		$this->assertStringContainsString( '<li>', $link );
		$this->assertStringContainsString( 'aria-current="page"', $link );
		$this->assertStringContainsString( 'April 2026', $link );
	}

	public function test__calendar_week_mod() {
		$this->assertSame( 1.0, calendar_week_mod( 8 ) );
		$this->assertSame( 6.0, calendar_week_mod( -1 ) );
	}

	public function test__allowed_tags() {
		$GLOBALS['allowedtags'] = [
			'a' => [ 'href' => true, 'title' => true ],
		];

		$result = allowed_tags();

		$this->assertStringContainsString( '&lt;a href=&quot;&quot; title=&quot;&quot;&gt;', $result );
	}

	public function test__wp_head() {
		$called = false;
		add_action(
			'wp_head',
			static function () use ( &$called ) {
				$called = true;
			}
		);

		wp_head();
		$this->assertTrue( $called );
	}

	public function test__wp_footer() {
		$called = false;
		add_action(
			'wp_footer',
			static function () use ( &$called ) {
				$called = true;
			}
		);

		wp_footer();
		$this->assertTrue( $called );
	}

	public function test__wp_body_open() {
		$called = false;
		add_action(
			'wp_body_open',
			static function () use ( &$called ) {
				$called = true;
			}
		);

		wp_body_open();
		$this->assertTrue( $called );
	}

	public function test__rsd_link() {
		ob_start();
		rsd_link();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'xmlrpc.php?rsd', $output );
		$this->assertStringContainsString( 'application/rsd+xml', $output );
	}

	public function test__wp_strict_cross_origin_referrer() {
		ob_start();
		wp_strict_cross_origin_referrer();
		$output = ob_get_clean();

		$this->assertStringContainsString( "name='referrer'", $output );
		$this->assertStringContainsString( 'strict-origin-when-cross-origin', $output );
	}

	public function test__wp_dependencies_unique_hosts() {
		$wp_scripts = new WP_Dependencies();
		$wp_scripts->queue = [ 'script-cdn', 'script-local' ];
		$wp_scripts->registered = [
			'script-cdn' => (object) [ 'src' => 'https://cdn1.example/script.js' ],
			'script-local' => (object) [ 'src' => 'https://unitest-wp-copy.loc/local.js' ],
		];

		$wp_styles = new WP_Dependencies();
		$wp_styles->queue = [ 'style-cdn' ];
		$wp_styles->registered = [
			'style-cdn' => (object) [ 'src' => 'https://cdn2.example/style.css' ],
		];

		$GLOBALS['wp_scripts'] = $wp_scripts;
		$GLOBALS['wp_styles'] = $wp_styles;

		$hosts = wp_dependencies_unique_hosts();

		$this->assertContains( 'cdn1.example', $hosts );
		$this->assertContains( 'cdn2.example', $hosts );
		$this->assertNotContains( 'unitest-wp-copy.loc', $hosts );
	}

	public function test__wp_resource_hints() {
		$GLOBALS['wp_scripts'] = null;
		$GLOBALS['wp_styles'] = null;

		add_filter(
			'wp_resource_hints',
			static function ( $urls, $relation_type ) {
				if ( 'dns-prefetch' === $relation_type ) {
					return [
						'https://cdn.example/static.js',
						'https://cdn.example/dup.js',
					];
				}
				if ( 'preconnect' === $relation_type ) {
					return [
						[
							'href' => 'https://fonts.example/css',
							'crossorigin' => 'anonymous',
						],
					];
				}
				return [];
			},
			10,
			2
		);

		ob_start();
		wp_resource_hints();
		$output = ob_get_clean();

		$this->assertStringContainsString( "rel='dns-prefetch'", $output );
		$this->assertStringContainsString( "href='//cdn.example'", $output );
		$this->assertStringContainsString( "rel='preconnect'", $output );
		$this->assertStringContainsString( "href='https://fonts.example'", $output );
		$this->assertSame( 1, substr_count( $output, "//cdn.example'" ) );
	}

	public function test__wp_preload_resources() {
		add_filter(
			'wp_preload_resources',
			static function () {
				return [
					[
						'href' => 'https://cdn.example/app.css',
						'as' => 'style',
					],
					[
						'href' => 'https://cdn.example/app.css',
						'as' => 'style',
					],
					[
						'as' => 'image',
						'imagesrcset' => 'https://cdn.example/img-1x.jpg 1x, https://cdn.example/img-2x.jpg 2x',
						'imagesizes' => '100vw',
					],
				];
			}
		);

		ob_start();
		wp_preload_resources();
		$output = ob_get_clean();

		$this->assertStringContainsString( "href='https://cdn.example/app.css'", $output );
		$this->assertSame( 1, substr_count( $output, "href='https://cdn.example/app.css'" ) );
		$this->assertStringContainsString( "imagesrcset='https://cdn.example/img-1x.jpg 1x, https://cdn.example/img-2x.jpg 2x'", $output );
	}

	public function test__get_language_attributes() {
		$GLOBALS['stub_wp_options']->language = 'fr-FR';
		$GLOBALS['stub_wp_options']->html_type = 'text/html';

		$this->assertSame( 'lang="fr-FR"', get_language_attributes( 'html' ) );

		$GLOBALS['stub_wp_options']->html_type = 'application/xhtml+xml';
		$this->assertSame( 'xml:lang="fr-FR"', get_language_attributes( 'xhtml' ) );
	}

	public function test__language_attributes() {
		$GLOBALS['stub_wp_options']->language = 'en-US';
		$GLOBALS['stub_wp_options']->html_type = 'text/html';

		ob_start();
		language_attributes();
		$output = ob_get_clean();

		$this->assertSame( 'lang="en-US"', $output );
	}

	public function test__wp_admin_css_color() {
		wp_admin_css_color( 'my-scheme', 'My Scheme', 'https://example.test/admin.css', [ '#111' ] );

		$this->assertArrayHasKey( 'my-scheme', $GLOBALS['_wp_admin_css_colors'] );
		$this->assertSame( 'My Scheme', $GLOBALS['_wp_admin_css_colors']['my-scheme']->name );
	}

	public function test__checked() {
		$this->assertSame( " checked='checked'", checked( 1, 1, false ) );
	}

	public function test__selected() {
		$this->assertSame( " selected='selected'", selected( 'x', 'x', false ) );
	}

	public function test__disabled() {
		$this->assertSame( " disabled='disabled'", disabled( true, true, false ) );
	}

	public function test__wp_readonly() {
		$this->assertSame( " readonly='readonly'", wp_readonly( 'on', 'on', false ) );
	}

	public function test___checked_selected_helper() {
		$this->assertSame( " checked='checked'", __checked_selected_helper( '1', 1, false, 'checked' ) );
		$this->assertSame( '', __checked_selected_helper( 'a', 'b', false, 'checked' ) );
	}

	public function test__wp_required_field_indicator() {
		$this->assertSame( '<span class="required">*</span>', wp_required_field_indicator() );
	}

	public function test__wp_required_field_message() {
		$this->assertStringContainsString( 'Required fields are marked', wp_required_field_message() );
		$this->assertStringContainsString( '<span class="required">*</span>', wp_required_field_message() );
	}

}
