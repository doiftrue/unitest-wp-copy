# Override functions

Every runtime-provided WordPress function is guarded by `function_exists()`.
Define a function before `Bootstrap::init()` to prevent Unitest WP Copy from
loading its implementation.

## Full override

Define the replacement in the global namespace in your test bootstrap:

```php
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

function sanitize_locale_name( $locale_name ) {
	return 'test-locale';
}

\Unitest_WP_Copy\Bootstrap::init();
\WP_Mock::bootstrap();
```

When the runtime reaches its guarded declaration:

```php
if ( ! function_exists( 'sanitize_locale_name' ) ) :
	// Runtime implementation.
endif;
```

the function already exists, so the runtime implementation is skipped. All code
in the PHP process then calls your replacement.

## When to use it

A full override is useful when:

- a copied function is not marked as mockable;
- the replacement should apply to the entire test process;
- a stable project-level test implementation is required.

Keep the replacement signature compatible with the WordPress function. Define
it once in the suite bootstrap, not inside an individual test: PHP functions
cannot be removed or redefined during the same process.

## Full override versus WP_Mock

Use [WP_Mock](https://github.com/10up/wp_mock) when the installed
`SYMBOLS-INFO.md` marks a function as **mockable** and individual tests need
different return values or expectations.

Use a full override when the original runtime function must never be loaded.
Because the replacement is defined first, it also bypasses the runtime's
WP_Mock-aware implementation.

Avoid overriding foundational functions unless the replacement preserves the
behavior required by other copied functions. A process-wide override can affect
every nested runtime call.
