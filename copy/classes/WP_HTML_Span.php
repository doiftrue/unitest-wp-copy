<?php

// ------------------auto-generated---------------------

// wp-includes/html-api/class-wp-html-span.php (WP 6.8.5)
if( ! class_exists( 'WP_HTML_Span' ) ) :
	class WP_HTML_Span {
		/**
		 * Byte offset into document where span begins.
		 *
		 * @since 6.2.0
		 *
		 * @var int
		 */
		public $start;
	
		/**
		 * Byte length of this span.
		 *
		 * @since 6.5.0
		 *
		 * @var int
		 */
		public $length;
	
		/**
		 * Constructor.
		 *
		 * @since 6.2.0
		 *
		 * @param int $start  Byte offset into document where replacement span begins.
		 * @param int $length Byte length of span.
		 */
		public function __construct( int $start, int $length ) {
			$this->start  = $start;
			$this->length = $length;
		}
	}
endif;

