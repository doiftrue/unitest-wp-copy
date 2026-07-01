# Symbol Eligibility Discussion


## WP_REST_Server
Analyzed source: WordPress `6.9.4` (`wp-core/wp-includes/version.php`)

### Why (Summary)

- Depends on missing REST symbols in this runtime (`WP_REST_Request`, `WP_REST_Response`, `WP_HTTP_Response`, `rest_get_server`, `rest_ensure_response`, `rest_convert_error_to_response`, `rest_url`, `get_rest_url`).
- Tightly coupled to live REST serving flow (HTTP headers, auth context, globals, route dispatch).
- Transitively requires `rest_api_init` and `create_initial_rest_routes` with broad controller/runtime wiring.

### Source Evidence (Key Points)

- `WP_REST_Server::serve_request()` lifecycle coupling: `wp-core/wp-includes/rest-api/class-wp-rest-server.php`
- `rest_get_server()` bootstrap and `rest_api_init`: `wp-core/wp-includes/rest-api.php`
- REST default lifecycle hook wiring: `wp-core/wp-includes/default-filters.php`
- Project-level ineligibility notes for related REST symbols: `config/functions/wp-includes/rest-api.php`
- Missing REST server/request/response symbols in runtime index: `wp-runtime/SYMBOLS-INFO.md`


## Sitemap
Analyzed source: WordPress `6.9.4` (`wp-core/wp-includes/version.php`)

### Needs Discussion (Candidates)

- `WP_Sitemaps`
- `wp_sitemaps_get_server`
- `wp_get_sitemap_providers`
- `wp_register_sitemap_provider`
- `get_sitemap_url`

### Why (Summary)

- `wp_sitemaps_get_server()` always constructs `WP_Sitemaps` and immediately calls `WP_Sitemaps::init()`.
- `WP_Sitemaps::init()` and `WP_Sitemaps::render_sitemaps()` are tightly coupled to rewrite/request lifecycle symbols that are not in this runtime (`add_rewrite_tag`, `add_rewrite_rule`, `get_query_var`, `status_header`, `wp_safe_redirect`, and live `WP_Query` flow).
- These symbols may still be useful if we intentionally expand runtime with rewrite/query wrappers and explicitly support a partial request lifecycle for sitemap routing.

### Source Evidence (Key Points)

- Sitemap public bootstrap functions: `wp-core/wp-includes/sitemaps.php`
- Lifecycle coupling (`init`, rewrites, rendering, redirects): `wp-core/wp-includes/sitemaps/class-wp-sitemaps.php`
- Missing helpers are already marked as runtime-ineligible in project config: `config/functions/wp-includes/functions.php`, `config/functions/wp-includes/rest-api.php`
