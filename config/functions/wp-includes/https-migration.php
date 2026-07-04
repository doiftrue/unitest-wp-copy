<?php

return [];

/*
Not suitable in isolated PHPUnit env:

wp_should_replace_insecure_home_url   // why: requires unavailable https_migration_required option state.
wp_replace_insecure_home_url          // why: depends on wp_should_replace_insecure_home_url() option state.
wp_update_urls_to_https               // why: requires unavailable option mutation APIs.
wp_update_https_migration_required    // why: requires unavailable option mutation APIs and installation state.
*/
