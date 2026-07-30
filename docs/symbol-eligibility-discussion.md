# Symbol Eligibility Discussion

## Core Class Review Status

The WordPress 7.0 core review covered all 756 named class declarations in 723
files under `wp-core/wp-includes/` and `wp-core/wp-admin/`, including declarations
nested inside `class_exists()` guards.

- 70 classes are active in `config/classes.php`.
- 18 classes below require an explicit runtime-boundary decision.
- 4 REST classes are tracked separately in [symbol-eligibility-discussion-rest-api.md](symbol-eligibility-discussion-rest-api.md).
- 1 sitemap class is tracked separately in [symbol-eligibility-discussion-sitemap.md](symbol-eligibility-discussion-sitemap.md).
- The remaining 663 declarations are covered by `config/not-suitable-files.md`.


## Block Rendering and Theme JSON Boundary

### Candidates

- `WP_Block` — `wp-includes/class-wp-block.php`
- `WP_Block_List` — `wp-includes/class-wp-block-list.php`
- `WP_Block_Processor` — `wp-includes/class-wp-block-processor.php`
- `WP_Block_Supports` — `wp-includes/class-wp-block-supports.php`
- `WP_Block_Metadata_Registry` — `wp-includes/class-wp-block-metadata-registry.php`
- `WP_Block_Templates_Registry` — `wp-includes/class-wp-block-templates-registry.php`
- `WP_Block_Patterns_Registry` — `wp-includes/class-wp-block-patterns-registry.php`
- `WP_Theme_JSON` — `wp-includes/class-wp-theme-json.php`
- `WP_Theme_JSON_Data` — `wp-includes/class-wp-theme-json-data.php`
- `WP_Theme_JSON_Resolver` — `wp-includes/class-wp-theme-json-resolver.php`
- `WP_Theme_JSON_Schema` — `wp-includes/class-wp-theme-json-schema.php`
- `WP_Duotone` — `wp-includes/class-wp-duotone.php`
- `WP_URL_Pattern_Prefixer` — `wp-includes/class-wp-url-pattern-prefixer.php`

### Why Discussion Is Required

- The block object/list/processor chain is in-memory at its core, but meaningful
  rendering crosses into registered block callbacks, global context, filters,
  block supports, and theme settings.
- Metadata, template, and pattern registries read manifests or PHP files and depend
  on theme/plugin path policy.
- `WP_Theme_JSON_Schema` is nearly pure, but its default path requires
  `WP_Theme_JSON::LATEST_SCHEMA`; adding it alone leaves a public default fatal.
- `WP_Theme_JSON`, its data wrapper, resolver, duotone processing, and URL prefixing
  form one broad theme/bootstrap subsystem with theme files, options, global styles,
  upload URLs, and runtime-dependent roots.

### Decision Needed

Choose whether to support a reduced block-render/theme-json subsystem, and define
which file/path/theme providers must be mocked versus intentionally omitted.


## Font Rendering Boundary

### Candidates

- `WP_Font_Face` — `wp-includes/fonts/class-wp-font-face.php`
- `WP_Font_Face_Resolver` — `wp-includes/fonts/class-wp-font-face-resolver.php`

### Why Discussion Is Required

- `WP_Font_Face` can format declarations in memory, but its main rendering flow is
  tied to font sources and HTML/style output.
- `WP_Font_Face_Resolver` depends on the Theme JSON resolver and theme-file URI
  resolution.
- `WP_Font_Collection` and `WP_Font_Library` are not candidates: their public
  collection contract includes filesystem and remote JSON loading and is recorded
  as unsuitable.

### Decision Needed

Decide whether font-face rendering should consume only caller-provided in-memory
data or retain WordPress file/theme resolution semantics.


## Abilities Registry Lifecycle

### Candidates

- `WP_Abilities_Registry` — `wp-includes/abilities-api/class-wp-abilities-registry.php`
- `WP_Ability_Categories_Registry` — `wp-includes/abilities-api/class-wp-ability-categories-registry.php`

### Why Discussion Is Required

- The `WP_Ability` and `WP_Ability_Category` value objects are already copied.
- Registry initialization is gated on the WordPress `init` action and emits
  subsystem initialization actions.
- `WP_Abilities_Registry` also requires the currently unavailable
  `wp_has_ability_category()` API.

### Decision Needed

Decide whether to add the complete Abilities registration-function surface and
model `init`, or keep only directly constructible value objects.


## Interactivity Runtime Boundary

### Candidate

- `WP_Interactivity_API` — `wp-includes/interactivity-api/class-wp-interactivity-api.php`

### Why Discussion Is Required

- The directives processor is already copied and operates on in-memory HTML.
- The main API manages global state, directive evaluation, server-side rendering,
  script-module integration, and output lifecycle behavior.

### Decision Needed

Define whether server-side Interactivity API rendering is an intended runtime
feature or whether only the deterministic directives processor should remain.
