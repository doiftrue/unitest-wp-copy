# Testing REST API code

The runtime provides an in-memory REST server for route registration,
validation, sanitization, dispatch, response links, OPTIONS, and batch requests.
It does not serve HTTP or load WordPress core endpoint controllers.

## Register and dispatch a route

```php
public function test_returns_an_item(): void {
	$server = rest_get_server();

	$server->register_route(
		'catalog/v1',
		'/catalog/v1/items/(?P<id>\d+)',
		[
			[
				'methods'             => 'GET',
				'callback'            => static fn( WP_REST_Request $request ) => [
					'id' => $request['id'],
				],
				'permission_callback' => '__return_true',
				'args'                => [
					'id' => [ 'type' => 'integer' ],
				],
			],
		]
	);

	$response = rest_do_request(
		new WP_REST_Request( 'GET', '/catalog/v1/items/42' )
	);

	self::assertSame( 200, $response->get_status() );
	self::assertSame( [ 'id' => 42 ], $response->get_data() );
}
```

The integer schema converts the route parameter from the string `"42"` to the
integer `42`.

## Reset REST state

`rest_get_server()` caches the server in a process-wide global. Always remove it
after a test that registers routes:

```php
protected function tearDown(): void {
	unset( $GLOBALS['wp_rest_server'] );
	\WP_Mock::tearDown();
	parent::tearDown();
}
```

Otherwise routes can leak into later tests.

## Route registration choices

Use direct `$server->register_route()` when the test is about request handling.
Use `register_rest_route()` when the test also needs WordPress's
`rest_api_init` timing check.

`rest_do_request()` returns the direct dispatch result. Serving filters normally
applied by WordPress's live `serve_request()` path, including automatic
`_fields` trimming and `Allow` headers, are available as functions but are not
applied automatically.

## Authentication defaults

The runtime defaults `current_user_can()` and `is_user_logged_in()` to `false`.
Override them for permission tests:

```php
\WP_Mock::userFunction( 'current_user_can' )
	->with( 'read_private_catalog_items' )
	->andReturn( true );
```
