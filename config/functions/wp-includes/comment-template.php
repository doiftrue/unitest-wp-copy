<?php

return [];

/*
Not suitable in isolated PHPUnit env:

All functions depend on get_comment() / get_post() / $wpdb / WP_Query / Walker_Comment (not available):

get_comment_author            // why: depends on get_comment() → DB
comment_author                // why: depends on get_comment_author() → DB
get_comment_author_email      // why: depends on get_comment() → DB
comment_author_email          // why: depends on get_comment_author_email() → DB
comment_author_email_link     // why: depends on get_comment_author_email_link() → DB
get_comment_author_email_link // why: depends on get_comment() → DB
get_comment_author_link       // why: depends on get_comment() → DB
comment_author_link           // why: depends on get_comment_author_link() → DB
get_comment_author_IP         // why: depends on get_comment() → DB
comment_author_IP             // why: depends on get_comment_author_IP() → DB
get_comment_author_url        // why: depends on get_comment() → DB
comment_author_url            // why: depends on get_comment_author_url() → DB
get_comment_author_url_link   // why: depends on get_comment_author_url() → DB
comment_author_url_link       // why: depends on get_comment_author_url_link() → DB
comment_class                 // why: depends on get_comment_class() → get_comment() → DB
get_comment_class             // why: depends on get_comment() → DB
get_comment_date              // why: depends on get_comment() → DB
comment_date                  // why: depends on get_comment_date() → DB
get_comment_excerpt           // why: depends on get_comment() → DB
comment_excerpt               // why: depends on get_comment_excerpt() → DB
get_comment_ID                // why: depends on $comment global → WP_Query loop
comment_ID                    // why: depends on get_comment_ID() → global
get_comment_link              // why: depends on get_comment() + get_permalink() → DB
get_comments_link             // why: depends on get_comments_link() → get_permalink() → DB
comments_link                 // why: depends on get_comments_link() → DB
get_comments_number           // why: depends on get_post() → DB
comments_number               // why: depends on get_comments_number_text() → DB
get_comments_number_text      // why: depends on get_comments_number() → DB
get_comment_text              // why: depends on get_comment() → DB
comment_text                  // why: depends on get_comment_text() → DB
get_comment_time              // why: depends on get_comment() → DB
comment_time                  // why: depends on get_comment_time() → DB
get_comment_type              // why: depends on get_comment() → DB
comment_type                  // why: depends on get_comment_type() → DB
get_trackback_url             // why: depends on get_permalink() → DB
trackback_url                 // why: depends on get_trackback_url() → DB
trackback_rdf                 // why: deprecated + depends on get_permalink() → DB
comments_open                 // why: depends on get_post() → DB
pings_open                    // why: depends on get_post() → DB
wp_comment_form_unfiltered_html_nonce // why: depends on get_post() + current_user_can() → DB
comments_template             // why: depends on $wpdb + filesystem → DB + I/O
comments_popup_link           // why: depends on get_comments_number() → DB
get_comment_reply_link        // why: depends on get_comment() + get_post() → DB
comment_reply_link            // why: depends on get_comment_reply_link() → DB
get_post_reply_link           // why: depends on get_post() → DB
post_reply_link               // why: depends on get_post_reply_link() → DB
get_cancel_comment_reply_link // why: depends on get_post() → DB
cancel_comment_reply_link     // why: depends on get_cancel_comment_reply_link() → DB
get_comment_id_fields         // why: depends on get_post() → DB
comment_id_fields             // why: depends on get_comment_id_fields() → DB
comment_form_title            // why: depends on get_post() + get_comment() → DB
_get_comment_reply_id         // why: depends on get_post() + get_comment() → DB
wp_list_comments              // why: depends on $wp_query + Walker_Comment (not available)
comment_form                  // why: depends on get_post() + is_user_logged_in() → DB
*/
