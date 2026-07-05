<?php

return [];

/*
Not suitable in isolated PHPUnit env:

register_block_pattern                       // why: requires WP_Block_Patterns_Registry and block-hooks runtime.
unregister_block_pattern                     // why: requires WP_Block_Patterns_Registry and block-hooks runtime.
register_block_pattern_category              // why: requires WP_Block_Pattern_Categories_Registry.
unregister_block_pattern_category            // why: requires WP_Block_Pattern_Categories_Registry.
_register_core_block_patterns_and_categories // why: depends on pattern registries and core pattern files.
wp_normalize_remote_block_pattern            // why: depends on WP_Block_Patterns_Registry normalization runtime.
_load_remote_block_patterns                  // why: depends on HTTP, transients, options, and pattern registries.
_load_remote_featured_patterns               // why: depends on HTTP, transients, options, and pattern registries.
_register_remote_theme_patterns              // why: depends on theme state, remote pattern loading, and registries.
_register_theme_block_patterns               // why: depends on theme filesystem pattern files and registries.
*/
