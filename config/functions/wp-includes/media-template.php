<?php

return [];

/*
Not suitable in isolated PHPUnit env:

wp_underscore_audio_template    // why: prints media-library admin templates
wp_underscore_video_template    // why: prints media-library admin templates
wp_print_media_templates        // why: depends on admin, post, attachment, and media modal lifecycle
*/
