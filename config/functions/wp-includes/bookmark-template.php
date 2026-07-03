<?php

return [];

/*
Not suitable in isolated PHPUnit env:

_walk_bookmarks     // why: depends on bookmark objects from get_bookmarks() → DB
wp_list_bookmarks   // why: depends on get_bookmarks() → DB + _walk_bookmarks()
*/
