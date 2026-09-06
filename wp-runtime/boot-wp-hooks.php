<?php

// Runtime-safe subset of the hooks registered by rest_api_default_filters().
add_filter( 'rest_pre_dispatch', 'rest_handle_options_request', 10, 3 );
