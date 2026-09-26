# Runtime-adapted classes

Some WordPress classes cannot run unchanged without a database or full
WordPress bootstrap. Unitest WP Copy provides reduced classes that combine
selected original WordPress methods with runtime-specific behavior.

The available public methods and properties are listed under
**Runtime-adapted classes** in the `SYMBOLS-INFO.md` file installed with your
package. Methods marked `[wp]` retain the copied WordPress implementation;
methods marked `[adapted]` intentionally behave differently in this runtime.

Extend the namespaced runtime class, not an internal generated trait.

## Extend `WP_REST_Server__Runtime`

The runtime REST server supports in-memory route registration and dispatch. A
guarded alias also exposes the base adapter as the global `WP_REST_Server`
class.

Create a subclass when a test needs a project-specific server:

```php
final class Plugin_REST_Server
	extends \Unitest_WP_Copy\WP_REST_Server__Runtime {

	public function register_plugin_routes(): void {
		$this->register_route(
			'my-plugin/v1',
			'/status',
			[
				[
					'methods'             => self::READABLE,
					'callback'            => static fn() => [ 'ready' => true ],
					'permission_callback' => '__return_true',
				],
			]
		);
	}
}

$server = new Plugin_REST_Server();
$server->register_plugin_routes();
$GLOBALS['wp_rest_server'] = $server;

$response = rest_do_request( '/my-plugin/v1/status' );

self::assertSame( [ 'ready' => true ], $response->get_data() );
```

Assign the instance to `$GLOBALS['wp_rest_server']` when code under test obtains
the server through `rest_get_server()`. Remove that global in `tearDown()` to
prevent registered routes from leaking into other tests.

Do not use this adapter for live HTTP serving: `serve_request()` is
intentionally unavailable. Dispatch requests in memory with
`rest_do_request()`.

## Extend `wpdb__Runtime`

The runtime `wpdb` adapter provides table-name properties and SQL construction
helpers, but it has no database connection or query implementation.

Extend it when code under test needs a small, deterministic database boundary.
For example, a test double can record a query and return predefined rows:

```php
final class Plugin_WPDB extends \Unitest_WP_Copy\wpdb__Runtime {

	public array $queries = [];
	public array $results = [];

	public function get_results( $query ) {
		$this->queries[] = $query;

		return $this->results;
	}
}

$wpdb = new Plugin_WPDB();
$wpdb->results = [
	(object) [ 'post_id' => 42 ],
];
$GLOBALS['wpdb'] = $wpdb;

$rows = my_plugin_find_posts();

self::assertSame( 42, $rows[0]->post_id );
self::assertStringContainsString( 'SELECT', $wpdb->queries[0] );
```

Assign the subclass to `$GLOBALS['wpdb']` when the tested code uses the global.
Restore the original object in `tearDown()`.

Keep added methods narrow and test-specific. The adapter is intended for SQL
building and deterministic doubles, not for recreating database behavior.

## Compatibility

Runtime-adapted classes expose only the members documented by the installed
`SYMBOLS-INFO.md`. Do not rely on private state or generated traits: their
contents can change as the supported WordPress line changes.
