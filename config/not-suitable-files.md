# Analyzed Files Without Suitable Functions

Paths are relative to `wp-core/`.

Each entry means that the source file was reviewed using
`docs/symbol-eligibility.md`, but no function from that file is currently active
in parser config. Check the corresponding rejection comments before reviewing
the file again. Remove the entry when at least one function from the file becomes
active in `config/functions/`.

- `wp-includes/author-template.php`
- `wp-includes/bookmark-template.php`
- `wp-includes/comment-template.php`
- `wp-includes/cron.php`
- `wp-includes/media-template.php`
- `wp-includes/post-template.php`
- `wp-includes/post-thumbnail-template.php`
- `wp-includes/query.php`
- `wp-includes/style-engine.php`
