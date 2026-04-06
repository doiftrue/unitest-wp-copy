<?php

// ------------------auto-generated---------------------

// wp-includes/html-api/class-wp-html-text-replacement.php (WP 6.8.3)
if( ! class_exists( 'WP_HTML_Text_Replacement' ) ) :
	class WP_HTML_Text_Replacement {
		/**
		 * Byte offset into document where replacement span begins.
		 *
		 * @since 6.2.0
		 *
		 * @var int
		 */
		public $start;
	
		/**
		 * Byte length of span being replaced.
		 *
		 * @since 6.5.0
		 *
		 * @var int
		 */
		public $length;
	
		/**
		 * Span of text to insert in document to replace existing content from start to end.
		 *
		 * @since 6.2.0
		 *
		 * @var string
		 */
		public $text;
	
		/**
		 * Constructor.
		 *
		 * @since 6.2.0
		 *
		 * @param int    $start  Byte offset into document where replacement span begins.
		 * @param int    $length Byte length of span in document being replaced.
		 * @param string $text   Span of text to insert in document to replace existing content from start to end.
		 */
		public function __construct( int $start, int $length, string $text ) {
			$this->start  = $start;
			$this->length = $length;
			$this->text   = $text;
		}
	}
endif;

