# Test REST API code

The runtime can register and dispatch REST routes in memory. No HTTP server is
started.

## Register and call a route

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

The request is validated, `id` is converted to an integer, and the callback is
executed directly.

## Reset the server

`rest_get_server()` caches its instance:

```php
protected function tearDown(): void {
	unset( $GLOBALS['wp_rest_server'] );
	parent::tearDown();
}
```

Always reset it after registering routes. When the test extends
`WP_Mock\Tools\TestCase`, the parent method handles WP_Mock cleanup.

## Test permissions

Authentication functions return `false` by default. Override a mockable boundary
when needed:

```php
\WP_Mock::userFunction( 'current_user_can' )
	->with( 'read_private_catalog_items' )
	->andReturn( true );
```

## Runtime boundary

The runtime supports route registration, validation, sanitization, dispatch,
OPTIONS, response links, and batch requests.

It does not serve live HTTP or load WordPress core endpoint controllers.
Post-dispatch behavior from `serve_request()`, such as automatic `_fields`
filtering and `Allow` headers, is not applied automatically.
