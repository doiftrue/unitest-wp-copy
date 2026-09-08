Symbol Eligibility Discussion
=======

Review Status
------
The candidate decisions below were refreshed against WordPress 7.1 source.

- 77 classes are active in `config/classes.php`.
- 13 classes below still require a runtime-boundary or adapter decision.
- 1 sitemap class is tracked separately in [symbol-eligibility-discussion-sitemap.md](symbol-eligibility-discussion-sitemap.md).
- `WP_REST_Server` is supported through the runtime-adapter mechanism.
- The remaining 664 declarations from the complete 7.0 inventory are covered by
  `config/not-suitable-files.md`.


Added After Re-review
------
These classes have dependency-complete, predictable behavior in the isolated
runtime and are now active:

- `WP_Block_Processor` — pure streaming parsing of block delimiters, HTML spans,
  and JSON attributes; depends only on `WP_HTML_Span` and available PHP/WP
  compatibility functions.
- `WP_Block_Templates_Registry` — in-memory plugin-template registry; its
  `WP_Block_Template`, `WP_Error`, `get_stylesheet()`,
  `get_default_block_template_types()`, and `wp_parse_args()` dependencies are
  available. The `register_block_template()` and
  `unregister_block_template()` wrappers are included with it.
- `WP_Font_Face` — validates caller-provided font declarations and generates CSS
  in memory before printing a `<style>` element; it does not resolve or read font
  files.
- `WP_Abilities_Registry` and `WP_Ability_Categories_Registry` — in-memory
  lifecycle-aware registries. Their complete public function surface is included;
  callers must fire `init` and register values on the corresponding
  `wp_abilities_api_*_init` actions, as in WordPress.


Block Rendering and Theme JSON Boundary
------
### Requires a reduced block-rendering adapter

- `WP_Block` — `wp-includes/class-wp-block.php`
- `WP_Block_List` — `wp-includes/class-wp-block-list.php`

`WP_Block_List` is only useful with `WP_Block`. The `WP_Block` constructor and
context propagation are mostly in-memory, but `render()` crosses registered
render callbacks, block bindings/supports, Interactivity API processing, script
and style queues, and script modules. Add these only after defining a
runtime-adapted `WP_Block` whose supported rendering contract and omitted
enqueue/interactivity behavior are explicit.

### Requires a block-support initialization contract

- `WP_Block_Supports` — `wp-includes/class-wp-block-supports.php`

The registry itself is in-memory, but useful behavior depends on the complete set
of support callbacks normally installed during WordPress bootstrap. Adding only
the class would expose an empty registry and incomplete public behavior. To add
it, select the supported block-support modules, include their callback dependency
chains, and initialize them deterministically in the runtime.

### Requires file/path policy or an in-memory-only adapter

- `WP_Block_Metadata_Registry` — `wp-includes/class-wp-block-metadata-registry.php`
- `WP_Block_Patterns_Registry` — `wp-includes/class-wp-block-patterns-registry.php`

The metadata registry is centered on manifest discovery, WordPress root
constants, theme/plugin paths, and filesystem reads. The patterns registry can
store inline content, but its public retrieval path also loads PHP/HTML files and
applies block hooks. Add either only after defining allowed roots and file loading,
or provide an adapter that accepts preloaded metadata/content and clearly omits
WordPress discovery semantics.

### Requires the complete Theme JSON subsystem

- `WP_Theme_JSON` — `wp-includes/class-wp-theme-json.php`
- `WP_Theme_JSON_Data` — `wp-includes/class-wp-theme-json-data.php`
- `WP_Theme_JSON_Resolver` — `wp-includes/class-wp-theme-json-resolver.php`
- `WP_Theme_JSON_Schema` — `wp-includes/class-wp-theme-json-schema.php`
- `WP_Duotone` — `wp-includes/class-wp-duotone.php`

`WP_Theme_JSON_Schema` is a deterministic array migrator, but its public default
references `WP_Theme_JSON::LATEST_SCHEMA`, so copying it alone leaves a fatal
default path. `WP_Theme_JSON_Data` also directly requires the full class.
`WP_Theme_JSON`, the resolver, and duotone rendering depend on block metadata,
style-engine helpers, theme files, global styles/options, upload URLs, and enqueue
state. Add this group only as a deliberately supported Theme JSON subsystem, or
extract a narrowly scoped schema adapter with its own schema-version constant.

### Requires an uploads URL provider

- `WP_URL_Pattern_Prefixer` — `wp-includes/class-wp-url-pattern-prefixer.php`

Caller-provided contexts make prefixing pure, and most default URL providers are
already available. The default constructor still calls `wp_upload_dir()`, whose
WordPress implementation is intentionally excluded because it includes upload
path, option, and filesystem behavior. Add the class only after introducing a
runtime-adapted uploads URL provider or changing the adapter contract to require
explicit contexts.


Font Resolution Boundary
------
### Requires Theme JSON providers

- `WP_Font_Face_Resolver` — `wp-includes/fonts/class-wp-font-face-resolver.php`

Its conversion helpers are deterministic, but both public entry points depend on
unavailable Theme JSON state: `wp_get_global_settings()` and
`WP_Theme_JSON_Resolver::get_style_variations()`. Add it with the complete Theme
JSON subsystem, or provide an adapter that accepts settings/style variations as
arguments while retaining the original conversion logic.

`WP_Font_Collection` and `WP_Font_Library` remain unsuitable because filesystem
and remote JSON loading are central to their public contract.


Interactivity Runtime Boundary
------
### Requires a processing-only adapter

- `WP_Interactivity_API` — `wp-includes/interactivity-api/class-wp-interactivity-api.php`

The copied directives processor is deterministic, but the full API also owns
process-global state, derived-state callbacks, script-module filters,
client-navigation attributes, request URL resolution, router styles, footer
output, and direct markup output. Do not copy the full class unchanged.

If stateful server-side directive rendering is needed, add a reduced adapter
limited to `state()`, `config()`, `process_directives()`, and processing-time
context/element access. Router markup, style enqueueing, footer hooks, and
script-module registration should remain unsupported unless the project adopts
those lifecycle boundaries explicitly.
