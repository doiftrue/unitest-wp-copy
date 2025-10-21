<?php

// ------------------auto-generated---------------------

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_timezone_string' ) ) :
	function wp_timezone_string() {
		$timezone_string = $GLOBALS['stub_wp_options']->timezone_string;
	
		if ( $timezone_string ) {
			return $timezone_string;
		}
	
		$offset  = (float) $GLOBALS['stub_wp_options']->gmt_offset;
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );
	
		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
	
		return $tz_offset;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_timezone' ) ) :
	function wp_timezone() {
		return new DateTimeZone( wp_timezone_string() );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'number_format_i18n' ) ) :
	function number_format_i18n( $number, $decimals = 0 ) {
		global $wp_locale;
	
		if ( isset( $wp_locale ) ) {
			$formatted = number_format( $number, absint( $decimals ), $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] );
		} else {
			$formatted = number_format( $number, absint( $decimals ) );
		}
	
		/**
		 * Filters the number formatted based on the locale.
		 *
		 * @since 2.8.0
		 * @since 4.9.0 The `$number` and `$decimals` parameters were added.
		 *
		 * @param string $formatted Converted number in string format.
		 * @param float  $number    The number to convert based on locale.
		 * @param int    $decimals  Precision of the number of decimal places.
		 */
		return apply_filters( 'number_format_i18n', $formatted, $number, $decimals );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'size_format' ) ) :
	function size_format( $bytes, $decimals = 0 ) {
		$quant = array(
			/* translators: Unit symbol for yottabyte. */
			_x( 'YB', 'unit symbol' ) => YB_IN_BYTES,
			/* translators: Unit symbol for zettabyte. */
			_x( 'ZB', 'unit symbol' ) => ZB_IN_BYTES,
			/* translators: Unit symbol for exabyte. */
			_x( 'EB', 'unit symbol' ) => EB_IN_BYTES,
			/* translators: Unit symbol for petabyte. */
			_x( 'PB', 'unit symbol' ) => PB_IN_BYTES,
			/* translators: Unit symbol for terabyte. */
			_x( 'TB', 'unit symbol' ) => TB_IN_BYTES,
			/* translators: Unit symbol for gigabyte. */
			_x( 'GB', 'unit symbol' ) => GB_IN_BYTES,
			/* translators: Unit symbol for megabyte. */
			_x( 'MB', 'unit symbol' ) => MB_IN_BYTES,
			/* translators: Unit symbol for kilobyte. */
			_x( 'KB', 'unit symbol' ) => KB_IN_BYTES,
			/* translators: Unit symbol for byte. */
			_x( 'B', 'unit symbol' )  => 1,
		);
	
		if ( 0 === $bytes ) {
			/* translators: Unit symbol for byte. */
			return number_format_i18n( 0, $decimals ) . ' ' . _x( 'B', 'unit symbol' );
		}
	
		foreach ( $quant as $unit => $mag ) {
			if ( (float) $bytes >= $mag ) {
				return number_format_i18n( $bytes / $mag, $decimals ) . ' ' . $unit;
			}
		}
	
		return false;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'maybe_serialize' ) ) :
	function maybe_serialize( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			return serialize( $data );
		}
	
		/*
		 * Double serialization is required for backward compatibility.
		 * See https://core.trac.wordpress.org/ticket/12930
		 * Also the world will end. See WP 3.6.1.
		 */
		if ( is_serialized( $data, false ) ) {
			return serialize( $data );
		}
	
		return $data;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'maybe_unserialize' ) ) :
	function maybe_unserialize( $data ) {
		if ( is_serialized( $data ) ) { // Don't attempt to unserialize data that wasn't serialized going in.
			return @unserialize( trim( $data ) );
		}
	
		return $data;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'is_serialized' ) ) :
	function is_serialized( $data, $strict = true ) {
		// If it isn't a string, it isn't serialized.
		if ( ! is_string( $data ) ) {
			return false;
		}
		$data = trim( $data );
		if ( 'N;' === $data ) {
			return true;
		}
		if ( strlen( $data ) < 4 ) {
			return false;
		}
		if ( ':' !== $data[1] ) {
			return false;
		}
		if ( $strict ) {
			$lastc = substr( $data, -1 );
			if ( ';' !== $lastc && '}' !== $lastc ) {
				return false;
			}
		} else {
			$semicolon = strpos( $data, ';' );
			$brace     = strpos( $data, '}' );
			// Either ; or } must exist.
			if ( false === $semicolon && false === $brace ) {
				return false;
			}
			// But neither must be in the first X characters.
			if ( false !== $semicolon && $semicolon < 3 ) {
				return false;
			}
			if ( false !== $brace && $brace < 4 ) {
				return false;
			}
		}
		$token = $data[0];
		switch ( $token ) {
			case 's':
				if ( $strict ) {
					if ( '"' !== substr( $data, -2, 1 ) ) {
						return false;
					}
				} elseif ( ! str_contains( $data, '"' ) ) {
					return false;
				}
				// Or else fall through.
			case 'a':
			case 'O':
			case 'E':
				return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
			case 'b':
			case 'i':
			case 'd':
				$end = $strict ? '$' : '';
				return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
		}
		return false;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'is_serialized_string' ) ) :
	function is_serialized_string( $data ) {
		// if it isn't a string, it isn't a serialized string.
		if ( ! is_string( $data ) ) {
			return false;
		}
		$data = trim( $data );
		if ( strlen( $data ) < 4 ) {
			return false;
		} elseif ( ':' !== $data[1] ) {
			return false;
		} elseif ( ! str_ends_with( $data, ';' ) ) {
			return false;
		} elseif ( 's' !== $data[0] ) {
			return false;
		} elseif ( '"' !== substr( $data, -2, 1 ) ) {
			return false;
		} else {
			return true;
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'build_query' ) ) :
	function build_query( $data ) {
		return _http_build_query( $data, null, '&', '', false );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_http_build_query' ) ) :
	function _http_build_query( $data, $prefix = null, $sep = null, $key = '', $urlencode = true ) {
		$ret = array();
	
		foreach ( (array) $data as $k => $v ) {
			if ( $urlencode ) {
				$k = urlencode( $k );
			}
	
			if ( is_int( $k ) && null !== $prefix ) {
				$k = $prefix . $k;
			}
	
			if ( ! empty( $key ) ) {
				$k = $key . '%5B' . $k . '%5D';
			}
	
			if ( null === $v ) {
				continue;
			} elseif ( false === $v ) {
				$v = '0';
			}
	
			if ( is_array( $v ) || is_object( $v ) ) {
				array_push( $ret, _http_build_query( $v, '', $sep, $k, $urlencode ) );
			} elseif ( $urlencode ) {
				array_push( $ret, $k . '=' . urlencode( $v ) );
			} else {
				array_push( $ret, $k . '=' . $v );
			}
		}
	
		if ( null === $sep ) {
			$sep = ini_get( 'arg_separator.output' );
		}
	
		return implode( $sep, $ret );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'add_query_arg' ) ) :
	function add_query_arg( ...$args ) {
		if ( is_array( $args[0] ) ) {
			if ( count( $args ) < 2 || false === $args[1] ) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $args[1];
			}
		} else {
			if ( count( $args ) < 3 || false === $args[2] ) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $args[2];
			}
		}
	
		$frag = strstr( $uri, '#' );
		if ( $frag ) {
			$uri = substr( $uri, 0, -strlen( $frag ) );
		} else {
			$frag = '';
		}
	
		if ( 0 === stripos( $uri, 'http://' ) ) {
			$protocol = 'http://';
			$uri      = substr( $uri, 7 );
		} elseif ( 0 === stripos( $uri, 'https://' ) ) {
			$protocol = 'https://';
			$uri      = substr( $uri, 8 );
		} else {
			$protocol = '';
		}
	
		if ( str_contains( $uri, '?' ) ) {
			list( $base, $query ) = explode( '?', $uri, 2 );
			$base                .= '?';
		} elseif ( $protocol || ! str_contains( $uri, '=' ) ) {
			$base  = $uri . '?';
			$query = '';
		} else {
			$base  = '';
			$query = $uri;
		}
	
		wp_parse_str( $query, $qs );
		$qs = urlencode_deep( $qs ); // This re-URL-encodes things that were already in the query string.
		if ( is_array( $args[0] ) ) {
			foreach ( $args[0] as $k => $v ) {
				$qs[ $k ] = $v;
			}
		} else {
			$qs[ $args[0] ] = $args[1];
		}
	
		foreach ( $qs as $k => $v ) {
			if ( false === $v ) {
				unset( $qs[ $k ] );
			}
		}
	
		$ret = build_query( $qs );
		$ret = trim( $ret, '?' );
		$ret = preg_replace( '#=(&|$)#', '$1', $ret );
		$ret = $protocol . $base . $ret . $frag;
		$ret = rtrim( $ret, '?' );
		$ret = str_replace( '?#', '#', $ret );
		return $ret;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'remove_query_arg' ) ) :
	function remove_query_arg( $key, $query = false ) {
		if ( is_array( $key ) ) { // Removing multiple keys.
			foreach ( $key as $k ) {
				$query = add_query_arg( $k, false, $query );
			}
			return $query;
		}
		return add_query_arg( $key, false, $query );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_get_nocache_headers' ) ) :
	function wp_get_nocache_headers() {
		$cache_control = 'no-cache, must-revalidate, max-age=0, no-store, private';
	
		$headers = array(
			'Expires'       => 'Wed, 11 Jan 1984 05:00:00 GMT',
			'Cache-Control' => $cache_control,
		);
	
		if ( function_exists( 'apply_filters' ) ) {
			/**
			 * Filters the cache-controlling HTTP headers that are used to prevent caching.
			 *
			 * @since 2.8.0
			 *
			 * @see wp_get_nocache_headers()
			 *
			 * @param array $headers Header names and field values.
			 */
			$headers = (array) apply_filters( 'nocache_headers', $headers );
		}
		$headers['Last-Modified'] = false;
		return $headers;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'bool_from_yn' ) ) :
	function bool_from_yn( $yn ) {
		return ( 'y' === strtolower( $yn ) );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'path_is_absolute' ) ) :
	function path_is_absolute( $path ) {
		/*
		 * Check to see if the path is a stream and check to see if its an actual
		 * path or file as realpath() does not support stream wrappers.
		 */
		if ( wp_is_stream( $path ) && ( is_dir( $path ) || is_file( $path ) ) ) {
			return true;
		}
	
		/*
		 * This is definitive if true but fails if $path does not exist or contains
		 * a symbolic link.
		 */
		if ( realpath( $path ) === $path ) {
			return true;
		}
	
		if ( strlen( $path ) === 0 || '.' === $path[0] ) {
			return false;
		}
	
		// Windows allows absolute paths like this.
		if ( preg_match( '#^[a-zA-Z]:\\\\#', $path ) ) {
			return true;
		}
	
		// A path starting with / or \ is absolute; anything else is relative.
		return ( '/' === $path[0] || '\\' === $path[0] );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'path_join' ) ) :
	function path_join( $base, $path ) {
		if ( path_is_absolute( $path ) ) {
			return $path;
		}
	
		return rtrim( $base, '/' ) . '/' . $path;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_normalize_path' ) ) :
	function wp_normalize_path( $path ) {
		$wrapper = '';
	
		if ( wp_is_stream( $path ) ) {
			list( $wrapper, $path ) = explode( '://', $path, 2 );
	
			$wrapper .= '://';
		}
	
		// Standardize all paths to use '/'.
		$path = str_replace( '\\', '/', $path );
	
		// Replace multiple slashes down to a singular, allowing for network shares having two slashes.
		$path = preg_replace( '|(?<=.)/+|', '/', $path );
	
		// Windows paths should uppercase the drive letter.
		if ( ':' === substr( $path, 1, 1 ) ) {
			$path = ucfirst( $path );
		}
	
		return $wrapper . $path;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_ext2type' ) ) :
	function wp_ext2type( $ext ) {
		$ext = strtolower( $ext );
	
		$ext2type = wp_get_ext_types();
		foreach ( $ext2type as $type => $exts ) {
			if ( in_array( $ext, $exts, true ) ) {
				return $type;
			}
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_get_default_extension_for_mime_type' ) ) :
	function wp_get_default_extension_for_mime_type( $mime_type ) {
		$extensions = explode( '|', array_search( $mime_type, wp_get_mime_types(), true ) );
	
		if ( empty( $extensions[0] ) ) {
			return false;
		}
	
		return $extensions[0];
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_check_filetype' ) ) :
	function wp_check_filetype( $filename, $mimes = null ) {
		if ( empty( $mimes ) ) {
			$mimes = get_allowed_mime_types();
		}
		$type = false;
		$ext  = false;
	
		foreach ( $mimes as $ext_preg => $mime_match ) {
			$ext_preg = '!\.(' . $ext_preg . ')$!i';
			if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
				$type = $mime_match;
				$ext  = $ext_matches[1];
				break;
			}
		}
	
		return compact( 'ext', 'type' );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_get_image_mime' ) ) :
	function wp_get_image_mime( $file ) {
		/*
		 * Use exif_imagetype() to check the mimetype if available or fall back to
		 * getimagesize() if exif isn't available. If either function throws an Exception
		 * we assume the file could not be validated.
		 */
		try {
			if ( is_callable( 'exif_imagetype' ) ) {
				$imagetype = exif_imagetype( $file );
				$mime      = ( $imagetype ) ? image_type_to_mime_type( $imagetype ) : false;
			} elseif ( function_exists( 'getimagesize' ) ) {
				// Don't silence errors when in debug mode, unless running unit tests.
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG && ! defined( 'WP_RUN_CORE_TESTS' ) ) {
					// Not using wp_getimagesize() here to avoid an infinite loop.
					$imagesize = getimagesize( $file );
				} else {
					$imagesize = @getimagesize( $file );
				}
	
				$mime = ( isset( $imagesize['mime'] ) ) ? $imagesize['mime'] : false;
			} else {
				$mime = false;
			}
	
			if ( false !== $mime ) {
				return $mime;
			}
	
			$magic = file_get_contents( $file, false, null, 0, 12 );
	
			if ( false === $magic ) {
				return false;
			}
	
			/*
			 * Add WebP fallback detection when image library doesn't support WebP.
			 * Note: detection values come from LibWebP, see
			 * https://github.com/webmproject/libwebp/blob/master/imageio/image_dec.c#L30
			 */
			$magic = bin2hex( $magic );
			if (
				// RIFF.
				( str_starts_with( $magic, '52494646' ) ) &&
				// WEBP.
				( 16 === strpos( $magic, '57454250' ) )
			) {
				$mime = 'image/webp';
			}
	
			/**
			 * Add AVIF fallback detection when image library doesn't support AVIF.
			 *
			 * Detection based on section 4.3.1 File-type box definition of the ISO/IEC 14496-12
			 * specification and the AV1-AVIF spec, see https://aomediacodec.github.io/av1-avif/v1.1.0.html#brands.
			 */
	
			// Divide the header string into 4 byte groups.
			$magic = str_split( $magic, 8 );
	
			if ( isset( $magic[1] ) && isset( $magic[2] ) && 'ftyp' === hex2bin( $magic[1] ) ) {
				if ( 'avif' === hex2bin( $magic[2] ) || 'avis' === hex2bin( $magic[2] ) ) {
					$mime = 'image/avif';
				} elseif ( 'heic' === hex2bin( $magic[2] ) ) {
					$mime = 'image/heic';
				} elseif ( 'heif' === hex2bin( $magic[2] ) ) {
					$mime = 'image/heif';
				} else {
					/*
					 * HEIC/HEIF images and image sequences/animations may have other strings here
					 * like mif1, msf1, etc. For now fall back to using finfo_file() to detect these.
					 */
					if ( extension_loaded( 'fileinfo' ) ) {
						$fileinfo  = finfo_open( FILEINFO_MIME_TYPE );
						$mime_type = finfo_file( $fileinfo, $file );
						finfo_close( $fileinfo );
	
						if ( wp_is_heic_image_mime_type( $mime_type ) ) {
							$mime = $mime_type;
						}
					}
				}
			}
		} catch ( Exception $e ) {
			$mime = false;
		}
	
		return $mime;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_get_mime_types' ) ) :
	function wp_get_mime_types() {
		/**
		 * Filters the list of mime types and file extensions.
		 *
		 * This filter should be used to add, not remove, mime types. To remove
		 * mime types, use the {@see 'upload_mimes'} filter.
		 *
		 * @since 3.5.0
		 *
		 * @param string[] $wp_get_mime_types Mime types keyed by the file extension regex
		 *                                    corresponding to those types.
		 */
		return apply_filters(
			'mime_types',
			array(
				// Image formats.
				'jpg|jpeg|jpe'                 => 'image/jpeg',
				'gif'                          => 'image/gif',
				'png'                          => 'image/png',
				'bmp'                          => 'image/bmp',
				'tiff|tif'                     => 'image/tiff',
				'webp'                         => 'image/webp',
				'avif'                         => 'image/avif',
				'ico'                          => 'image/x-icon',
	
				// TODO: Needs improvement. All images with the following mime types seem to have .heic file extension.
				'heic'                         => 'image/heic',
				'heif'                         => 'image/heif',
				'heics'                        => 'image/heic-sequence',
				'heifs'                        => 'image/heif-sequence',
	
				// Video formats.
				'asf|asx'                      => 'video/x-ms-asf',
				'wmv'                          => 'video/x-ms-wmv',
				'wmx'                          => 'video/x-ms-wmx',
				'wm'                           => 'video/x-ms-wm',
				'avi'                          => 'video/avi',
				'divx'                         => 'video/divx',
				'flv'                          => 'video/x-flv',
				'mov|qt'                       => 'video/quicktime',
				'mpeg|mpg|mpe'                 => 'video/mpeg',
				'mp4|m4v'                      => 'video/mp4',
				'ogv'                          => 'video/ogg',
				'webm'                         => 'video/webm',
				'mkv'                          => 'video/x-matroska',
				'3gp|3gpp'                     => 'video/3gpp',  // Can also be audio.
				'3g2|3gp2'                     => 'video/3gpp2', // Can also be audio.
				// Text formats.
				'txt|asc|c|cc|h|srt'           => 'text/plain',
				'csv'                          => 'text/csv',
				'tsv'                          => 'text/tab-separated-values',
				'ics'                          => 'text/calendar',
				'rtx'                          => 'text/richtext',
				'css'                          => 'text/css',
				'htm|html'                     => 'text/html',
				'vtt'                          => 'text/vtt',
				'dfxp'                         => 'application/ttaf+xml',
				// Audio formats.
				'mp3|m4a|m4b'                  => 'audio/mpeg',
				'aac'                          => 'audio/aac',
				'ra|ram'                       => 'audio/x-realaudio',
				'wav|x-wav'                    => 'audio/wav',
				'ogg|oga'                      => 'audio/ogg',
				'flac'                         => 'audio/flac',
				'mid|midi'                     => 'audio/midi',
				'wma'                          => 'audio/x-ms-wma',
				'wax'                          => 'audio/x-ms-wax',
				'mka'                          => 'audio/x-matroska',
				// Misc application formats.
				'rtf'                          => 'application/rtf',
				'js'                           => 'application/javascript',
				'pdf'                          => 'application/pdf',
				'swf'                          => 'application/x-shockwave-flash',
				'class'                        => 'application/java',
				'tar'                          => 'application/x-tar',
				'zip'                          => 'application/zip',
				'gz|gzip'                      => 'application/x-gzip',
				'rar'                          => 'application/rar',
				'7z'                           => 'application/x-7z-compressed',
				'exe'                          => 'application/x-msdownload',
				'psd'                          => 'application/octet-stream',
				'xcf'                          => 'application/octet-stream',
				// MS Office formats.
				'doc'                          => 'application/msword',
				'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
				'wri'                          => 'application/vnd.ms-write',
				'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
				'mdb'                          => 'application/vnd.ms-access',
				'mpp'                          => 'application/vnd.ms-project',
				'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
				'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
				'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
				'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
				'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
				'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
				'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
				'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
				'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
				'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
				'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
				'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
				'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
				'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
				'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
				'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
				'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
				'oxps'                         => 'application/oxps',
				'xps'                          => 'application/vnd.ms-xpsdocument',
				// OpenOffice formats.
				'odt'                          => 'application/vnd.oasis.opendocument.text',
				'odp'                          => 'application/vnd.oasis.opendocument.presentation',
				'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
				'odg'                          => 'application/vnd.oasis.opendocument.graphics',
				'odc'                          => 'application/vnd.oasis.opendocument.chart',
				'odb'                          => 'application/vnd.oasis.opendocument.database',
				'odf'                          => 'application/vnd.oasis.opendocument.formula',
				// WordPerfect formats.
				'wp|wpd'                       => 'application/wordperfect',
				// iWork formats.
				'key'                          => 'application/vnd.apple.keynote',
				'numbers'                      => 'application/vnd.apple.numbers',
				'pages'                        => 'application/vnd.apple.pages',
			)
		);
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_get_ext_types' ) ) :
	function wp_get_ext_types() {
	
		/**
		 * Filters file type based on the extension name.
		 *
		 * @since 2.5.0
		 *
		 * @see wp_ext2type()
		 *
		 * @param array[] $ext2type Multi-dimensional array of file extensions types keyed by the type of file.
		 */
		return apply_filters(
			'ext2type',
			array(
				'image'       => array( 'jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tif', 'tiff', 'ico', 'heic', 'heif', 'webp', 'avif' ),
				'audio'       => array( 'aac', 'ac3', 'aif', 'aiff', 'flac', 'm3a', 'm4a', 'm4b', 'mka', 'mp1', 'mp2', 'mp3', 'ogg', 'oga', 'ram', 'wav', 'wma' ),
				'video'       => array( '3g2', '3gp', '3gpp', 'asf', 'avi', 'divx', 'dv', 'flv', 'm4v', 'mkv', 'mov', 'mp4', 'mpeg', 'mpg', 'mpv', 'ogm', 'ogv', 'qt', 'rm', 'vob', 'wmv' ),
				'document'    => array( 'doc', 'docx', 'docm', 'dotm', 'odt', 'pages', 'pdf', 'xps', 'oxps', 'rtf', 'wp', 'wpd', 'psd', 'xcf' ),
				'spreadsheet' => array( 'numbers', 'ods', 'xls', 'xlsx', 'xlsm', 'xlsb' ),
				'interactive' => array( 'swf', 'key', 'ppt', 'pptx', 'pptm', 'pps', 'ppsx', 'ppsm', 'sldx', 'sldm', 'odp' ),
				'text'        => array( 'asc', 'csv', 'tsv', 'txt' ),
				'archive'     => array( 'bz2', 'cab', 'dmg', 'gz', 'rar', 'sea', 'sit', 'sqx', 'tar', 'tgz', 'zip', '7z' ),
				'code'        => array( 'css', 'htm', 'html', 'php', 'js' ),
			)
		);
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'get_allowed_mime_types' ) ) :
	function get_allowed_mime_types( $user = null ) {
		$t = wp_get_mime_types();
	
		unset( $t['swf'], $t['exe'] );
		if ( function_exists( 'current_user_can' ) ) {
			$unfiltered = $user ? user_can( $user, 'unfiltered_html' ) : current_user_can( 'unfiltered_html' );
		}
	
		if ( empty( $unfiltered ) ) {
			unset( $t['htm|html'], $t['js'] );
		}
	
		/**
		 * Filters the list of allowed mime types and file extensions.
		 *
		 * @since 2.0.0
		 *
		 * @param array            $t    Mime types keyed by the file extension regex corresponding to those types.
		 * @param int|WP_User|null $user User ID, User object or null if not provided (indicates current user).
		 */
		return apply_filters( 'upload_mimes', $t, $user );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_json_encode' ) ) :
	function wp_json_encode( $value, $flags = 0, $depth = 512 ) {
		$json = json_encode( $value, $flags, $depth );
	
		// If json_encode() was successful, no need to do more confidence checking.
		if ( false !== $json ) {
			return $json;
		}
	
		try {
			$value = _wp_json_sanity_check( $value, $depth );
		} catch ( Exception $e ) {
			return false;
		}
	
		return json_encode( $value, $flags, $depth );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_wp_json_sanity_check' ) ) :
	function _wp_json_sanity_check( $value, $depth ) {
		if ( $depth < 0 ) {
			throw new Exception( 'Reached depth limit' );
		}
	
		if ( is_array( $value ) ) {
			$output = array();
			foreach ( $value as $id => $el ) {
				// Don't forget to sanitize the ID!
				if ( is_string( $id ) ) {
					$clean_id = _wp_json_convert_string( $id );
				} else {
					$clean_id = $id;
				}
	
				// Check the element type, so that we're only recursing if we really have to.
				if ( is_array( $el ) || is_object( $el ) ) {
					$output[ $clean_id ] = _wp_json_sanity_check( $el, $depth - 1 );
				} elseif ( is_string( $el ) ) {
					$output[ $clean_id ] = _wp_json_convert_string( $el );
				} else {
					$output[ $clean_id ] = $el;
				}
			}
		} elseif ( is_object( $value ) ) {
			$output = new stdClass();
			foreach ( $value as $id => $el ) {
				if ( is_string( $id ) ) {
					$clean_id = _wp_json_convert_string( $id );
				} else {
					$clean_id = $id;
				}
	
				if ( is_array( $el ) || is_object( $el ) ) {
					$output->$clean_id = _wp_json_sanity_check( $el, $depth - 1 );
				} elseif ( is_string( $el ) ) {
					$output->$clean_id = _wp_json_convert_string( $el );
				} else {
					$output->$clean_id = $el;
				}
			}
		} elseif ( is_string( $value ) ) {
			return _wp_json_convert_string( $value );
		} else {
			return $value;
		}
	
		return $output;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_wp_json_convert_string' ) ) :
	function _wp_json_convert_string( $input_string ) {
		static $use_mb = null;
		if ( is_null( $use_mb ) ) {
			$use_mb = function_exists( 'mb_convert_encoding' );
		}
	
		if ( $use_mb ) {
			$encoding = mb_detect_encoding( $input_string, mb_detect_order(), true );
			if ( $encoding ) {
				return mb_convert_encoding( $input_string, 'UTF-8', $encoding );
			} else {
				return mb_convert_encoding( $input_string, 'UTF-8', 'UTF-8' );
			}
		} else {
			return wp_check_invalid_utf8( $input_string, true );
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_wp_json_prepare_data' ) ) :
	function _wp_json_prepare_data( $value ) {
		_deprecated_function( __FUNCTION__, '5.3.0' );
		return $value;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_check_jsonp_callback' ) ) :
	function wp_check_jsonp_callback( $callback ) {
		if ( ! is_string( $callback ) ) {
			return false;
		}
	
		preg_replace( '/[^\w\.]/', '', $callback, -1, $illegal_char_count );
	
		return 0 === $illegal_char_count;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_json_file_decode' ) ) :
	function wp_json_file_decode( $filename, $options = array() ) {
		$result   = null;
		$filename = wp_normalize_path( realpath( $filename ) );
	
		if ( ! $filename ) {
			wp_trigger_error(
				__FUNCTION__,
				sprintf(
					/* translators: %s: Path to the JSON file. */
					__( "File %s doesn't exist!" ),
					$filename
				)
			);
			return $result;
		}
	
		$options      = wp_parse_args( $options, array( 'associative' => false ) );
		$decoded_file = json_decode( file_get_contents( $filename ), $options['associative'] );
	
		if ( JSON_ERROR_NONE !== json_last_error() ) {
			wp_trigger_error(
				__FUNCTION__,
				sprintf(
					/* translators: 1: Path to the JSON file, 2: Error message. */
					__( 'Error when decoding a JSON file at path %1$s: %2$s' ),
					$filename,
					json_last_error_msg()
				)
			);
			return $result;
		}
	
		return $decoded_file;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'smilies_init' ) ) :
	function smilies_init() {
		global $wpsmiliestrans, $wp_smiliessearch;
	
		// Don't bother setting up smilies if they are disabled.
		if ( ! $GLOBALS['stub_wp_options']->use_smilies ) {
			return;
		}
	
		if ( ! isset( $wpsmiliestrans ) ) {
			$wpsmiliestrans = array(
				':mrgreen:' => 'mrgreen.png',
				':neutral:' => "\xf0\x9f\x98\x90",
				':twisted:' => "\xf0\x9f\x98\x88",
				':arrow:'   => "\xe2\x9e\xa1",
				':shock:'   => "\xf0\x9f\x98\xaf",
				':smile:'   => "\xf0\x9f\x99\x82",
				':???:'     => "\xf0\x9f\x98\x95",
				':cool:'    => "\xf0\x9f\x98\x8e",
				':evil:'    => "\xf0\x9f\x91\xbf",
				':grin:'    => "\xf0\x9f\x98\x80",
				':idea:'    => "\xf0\x9f\x92\xa1",
				':oops:'    => "\xf0\x9f\x98\xb3",
				':razz:'    => "\xf0\x9f\x98\x9b",
				':roll:'    => "\xf0\x9f\x99\x84",
				':wink:'    => "\xf0\x9f\x98\x89",
				':cry:'     => "\xf0\x9f\x98\xa5",
				':eek:'     => "\xf0\x9f\x98\xae",
				':lol:'     => "\xf0\x9f\x98\x86",
				':mad:'     => "\xf0\x9f\x98\xa1",
				':sad:'     => "\xf0\x9f\x99\x81",
				'8-)'       => "\xf0\x9f\x98\x8e",
				'8-O'       => "\xf0\x9f\x98\xaf",
				':-('       => "\xf0\x9f\x99\x81",
				':-)'       => "\xf0\x9f\x99\x82",
				':-?'       => "\xf0\x9f\x98\x95",
				':-D'       => "\xf0\x9f\x98\x80",
				':-P'       => "\xf0\x9f\x98\x9b",
				':-o'       => "\xf0\x9f\x98\xae",
				':-x'       => "\xf0\x9f\x98\xa1",
				':-|'       => "\xf0\x9f\x98\x90",
				';-)'       => "\xf0\x9f\x98\x89",
				// This one transformation breaks regular text with frequency.
				//     '8)' => "\xf0\x9f\x98\x8e",
				'8O'        => "\xf0\x9f\x98\xaf",
				':('        => "\xf0\x9f\x99\x81",
				':)'        => "\xf0\x9f\x99\x82",
				':?'        => "\xf0\x9f\x98\x95",
				':D'        => "\xf0\x9f\x98\x80",
				':P'        => "\xf0\x9f\x98\x9b",
				':o'        => "\xf0\x9f\x98\xae",
				':x'        => "\xf0\x9f\x98\xa1",
				':|'        => "\xf0\x9f\x98\x90",
				';)'        => "\xf0\x9f\x98\x89",
				':!:'       => "\xe2\x9d\x97",
				':?:'       => "\xe2\x9d\x93",
			);
		}
	
		/**
		 * Filters all the smilies.
		 *
		 * This filter must be added before `smilies_init` is run, as
		 * it is normally only run once to setup the smilies regex.
		 *
		 * @since 4.7.0
		 *
		 * @param string[] $wpsmiliestrans List of the smilies' hexadecimal representations, keyed by their smily code.
		 */
		$wpsmiliestrans = apply_filters( 'smilies', $wpsmiliestrans );
	
		if ( count( $wpsmiliestrans ) === 0 ) {
			return;
		}
	
		/*
		 * NOTE: we sort the smilies in reverse key order. This is to make sure
		 * we match the longest possible smilie (:???: vs :?) as the regular
		 * expression used below is first-match
		 */
		krsort( $wpsmiliestrans );
	
		$spaces = wp_spaces_regexp();
	
		// Begin first "subpattern".
		$wp_smiliessearch = '/(?<=' . $spaces . '|^)';
	
		$subchar = '';
		foreach ( (array) $wpsmiliestrans as $smiley => $img ) {
			$firstchar = substr( $smiley, 0, 1 );
			$rest      = substr( $smiley, 1 );
	
			// New subpattern?
			if ( $firstchar !== $subchar ) {
				if ( '' !== $subchar ) {
					$wp_smiliessearch .= ')(?=' . $spaces . '|$)';  // End previous "subpattern".
					$wp_smiliessearch .= '|(?<=' . $spaces . '|^)'; // Begin another "subpattern".
				}
	
				$subchar           = $firstchar;
				$wp_smiliessearch .= preg_quote( $firstchar, '/' ) . '(?:';
			} else {
				$wp_smiliessearch .= '|';
			}
	
			$wp_smiliessearch .= preg_quote( $rest, '/' );
		}
	
		$wp_smiliessearch .= ')(?=' . $spaces . '|$)/m';
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_parse_args' ) ) :
	function wp_parse_args( $args, $defaults = array() ) {
		if ( is_object( $args ) ) {
			$parsed_args = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$parsed_args =& $args;
		} else {
			wp_parse_str( $args, $parsed_args );
		}
	
		if ( is_array( $defaults ) && $defaults ) {
			return array_merge( $defaults, $parsed_args );
		}
		return $parsed_args;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_parse_list' ) ) :
	function wp_parse_list( $input_list ) {
		if ( ! is_array( $input_list ) ) {
			return preg_split( '/[\s,]+/', $input_list, -1, PREG_SPLIT_NO_EMPTY );
		}
	
		// Validate all entries of the list are scalar.
		$input_list = array_filter( $input_list, 'is_scalar' );
	
		return $input_list;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_parse_id_list' ) ) :
	function wp_parse_id_list( $input_list ) {
		$input_list = wp_parse_list( $input_list );
	
		return array_unique( array_map( 'absint', $input_list ) );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_parse_slug_list' ) ) :
	function wp_parse_slug_list( $input_list ) {
		$input_list = wp_parse_list( $input_list );
	
		return array_unique( array_map( 'sanitize_title', $input_list ) );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_array_slice_assoc' ) ) :
	function wp_array_slice_assoc( $input_array, $keys ) {
		$slice = array();
	
		foreach ( $keys as $key ) {
			if ( isset( $input_array[ $key ] ) ) {
				$slice[ $key ] = $input_array[ $key ];
			}
		}
	
		return $slice;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_recursive_ksort' ) ) :
	function wp_recursive_ksort( &$input_array ) {
		foreach ( $input_array as &$value ) {
			if ( is_array( $value ) ) {
				wp_recursive_ksort( $value );
			}
		}
	
		ksort( $input_array );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_wp_array_get' ) ) :
	function _wp_array_get( $input_array, $path, $default_value = null ) {
		// Confirm $path is valid.
		if ( ! is_array( $path ) || 0 === count( $path ) ) {
			return $default_value;
		}
	
		foreach ( $path as $path_element ) {
			if ( ! is_array( $input_array ) ) {
				return $default_value;
			}
	
			if ( is_string( $path_element )
				|| is_integer( $path_element )
				|| null === $path_element
			) {
				/*
				 * Check if the path element exists in the input array.
				 * We check with `isset()` first, as it is a lot faster
				 * than `array_key_exists()`.
				 */
				if ( isset( $input_array[ $path_element ] ) ) {
					$input_array = $input_array[ $path_element ];
					continue;
				}
	
				/*
				 * If `isset()` returns false, we check with `array_key_exists()`,
				 * which also checks for `null` values.
				 */
				if ( array_key_exists( $path_element, $input_array ) ) {
					$input_array = $input_array[ $path_element ];
					continue;
				}
			}
	
			return $default_value;
		}
	
		return $input_array;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_wp_array_set' ) ) :
	function _wp_array_set( &$input_array, $path, $value = null ) {
		// Confirm $input_array is valid.
		if ( ! is_array( $input_array ) ) {
			return;
		}
	
		// Confirm $path is valid.
		if ( ! is_array( $path ) ) {
			return;
		}
	
		$path_length = count( $path );
	
		if ( 0 === $path_length ) {
			return;
		}
	
		foreach ( $path as $path_element ) {
			if (
				! is_string( $path_element ) && ! is_integer( $path_element ) &&
				! is_null( $path_element )
			) {
				return;
			}
		}
	
		for ( $i = 0; $i < $path_length - 1; ++$i ) {
			$path_element = $path[ $i ];
			if (
				! array_key_exists( $path_element, $input_array ) ||
				! is_array( $input_array[ $path_element ] )
			) {
				$input_array[ $path_element ] = array();
			}
			$input_array = &$input_array[ $path_element ];
		}
	
		$input_array[ $path[ $i ] ] = $value;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_wp_to_kebab_case' ) ) :
	function _wp_to_kebab_case( $input_string ) {
		// Ignore the camelCase names for variables so the names are the same as lodash so comparing and porting new changes is easier.
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	
		/*
		 * Some notable things we've removed compared to the lodash version are:
		 *
		 * - non-alphanumeric characters: rsAstralRange, rsEmoji, etc
		 * - the groups that processed the apostrophe, as it's removed before passing the string to preg_match: rsApos, rsOptContrLower, and rsOptContrUpper
		 *
		 */
	
		/** Used to compose unicode character classes. */
		$rsLowerRange       = 'a-z\\xdf-\\xf6\\xf8-\\xff';
		$rsNonCharRange     = '\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf';
		$rsPunctuationRange = '\\x{2000}-\\x{206f}';
		$rsSpaceRange       = ' \\t\\x0b\\f\\xa0\\x{feff}\\n\\r\\x{2028}\\x{2029}\\x{1680}\\x{180e}\\x{2000}\\x{2001}\\x{2002}\\x{2003}\\x{2004}\\x{2005}\\x{2006}\\x{2007}\\x{2008}\\x{2009}\\x{200a}\\x{202f}\\x{205f}\\x{3000}';
		$rsUpperRange       = 'A-Z\\xc0-\\xd6\\xd8-\\xde';
		$rsBreakRange       = $rsNonCharRange . $rsPunctuationRange . $rsSpaceRange;
	
		/** Used to compose unicode capture groups. */
		$rsBreak  = '[' . $rsBreakRange . ']';
		$rsDigits = '\\d+'; // The last lodash version in GitHub uses a single digit here and expands it when in use.
		$rsLower  = '[' . $rsLowerRange . ']';
		$rsMisc   = '[^' . $rsBreakRange . $rsDigits . $rsLowerRange . $rsUpperRange . ']';
		$rsUpper  = '[' . $rsUpperRange . ']';
	
		/** Used to compose unicode regexes. */
		$rsMiscLower = '(?:' . $rsLower . '|' . $rsMisc . ')';
		$rsMiscUpper = '(?:' . $rsUpper . '|' . $rsMisc . ')';
		$rsOrdLower  = '\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])';
		$rsOrdUpper  = '\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])';
	
		$regexp = '/' . implode(
			'|',
			array(
				$rsUpper . '?' . $rsLower . '+' . '(?=' . implode( '|', array( $rsBreak, $rsUpper, '$' ) ) . ')',
				$rsMiscUpper . '+' . '(?=' . implode( '|', array( $rsBreak, $rsUpper . $rsMiscLower, '$' ) ) . ')',
				$rsUpper . '?' . $rsMiscLower . '+',
				$rsUpper . '+',
				$rsOrdUpper,
				$rsOrdLower,
				$rsDigits,
			)
		) . '/u';
	
		preg_match_all( $regexp, str_replace( "'", '', $input_string ), $matches );
		return strtolower( implode( '-', $matches[0] ) );
		// phpcs:enable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_is_numeric_array' ) ) :
	function wp_is_numeric_array( $data ) {
		if ( ! is_array( $data ) ) {
			return false;
		}
	
		$keys        = array_keys( $data );
		$string_keys = array_filter( $keys, 'is_string' );
	
		return count( $string_keys ) === 0;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_filter_object_list' ) ) :
	function wp_filter_object_list( $input_list, $args = array(), $operator = 'and', $field = false ) {
		if ( ! is_array( $input_list ) ) {
			return array();
		}
	
		$util = new WP_List_Util( $input_list );
	
		$util->filter( $args, $operator );
	
		if ( $field ) {
			$util->pluck( $field );
		}
	
		return $util->get_output();
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_list_filter' ) ) :
	function wp_list_filter( $input_list, $args = array(), $operator = 'AND' ) {
		return wp_filter_object_list( $input_list, $args, $operator );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_list_pluck' ) ) :
	function wp_list_pluck( $input_list, $field, $index_key = null ) {
		if ( ! is_array( $input_list ) ) {
			return array();
		}
	
		$util = new WP_List_Util( $input_list );
	
		return $util->pluck( $field, $index_key );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_list_sort' ) ) :
	function wp_list_sort( $input_list, $orderby = array(), $order = 'ASC', $preserve_keys = false ) {
		if ( ! is_array( $input_list ) ) {
			return array();
		}
	
		$util = new WP_List_Util( $input_list );
	
		return $util->sort( $orderby, $order, $preserve_keys );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_deprecated_function' ) ) :
	function _deprecated_function( $function_name, $version, $replacement = '' ) {
	
		/**
		 * Fires when a deprecated function is called.
		 *
		 * @since 2.5.0
		 *
		 * @param string $function_name The function that was called.
		 * @param string $replacement   The function that should have been called.
		 * @param string $version       The version of WordPress that deprecated the function.
		 */
		do_action( 'deprecated_function_run', $function_name, $replacement, $version );
	
		/**
		 * Filters whether to trigger an error for deprecated functions.
		 *
		 * @since 2.5.0
		 *
		 * @param bool $trigger Whether to trigger the error for deprecated functions. Default true.
		 */
		if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
			if ( function_exists( '__' ) ) {
				if ( $replacement ) {
					$message = sprintf(
						/* translators: 1: PHP function name, 2: Version number, 3: Alternative function name. */
						__( 'Function %1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.' ),
						$function_name,
						$version,
						$replacement
					);
				} else {
					$message = sprintf(
						/* translators: 1: PHP function name, 2: Version number. */
						__( 'Function %1$s is <strong>deprecated</strong> since version %2$s with no alternative available.' ),
						$function_name,
						$version
					);
				}
			} else {
				if ( $replacement ) {
					$message = sprintf(
						'Function %1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.',
						$function_name,
						$version,
						$replacement
					);
				} else {
					$message = sprintf(
						'Function %1$s is <strong>deprecated</strong> since version %2$s with no alternative available.',
						$function_name,
						$version
					);
				}
			}
	
			wp_trigger_error( '', $message, E_USER_DEPRECATED );
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_deprecated_argument' ) ) :
	function _deprecated_argument( $function_name, $version, $message = '' ) {
	
		/**
		 * Fires when a deprecated argument is called.
		 *
		 * @since 3.0.0
		 *
		 * @param string $function_name The function that was called.
		 * @param string $message       A message regarding the change.
		 * @param string $version       The version of WordPress that deprecated the argument used.
		 */
		do_action( 'deprecated_argument_run', $function_name, $message, $version );
	
		/**
		 * Filters whether to trigger an error for deprecated arguments.
		 *
		 * @since 3.0.0
		 *
		 * @param bool $trigger Whether to trigger the error for deprecated arguments. Default true.
		 */
		if ( WP_DEBUG && apply_filters( 'deprecated_argument_trigger_error', true ) ) {
			if ( function_exists( '__' ) ) {
				if ( $message ) {
					$message = sprintf(
						/* translators: 1: PHP function name, 2: Version number, 3: Optional message regarding the change. */
						__( 'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s! %3$s' ),
						$function_name,
						$version,
						$message
					);
				} else {
					$message = sprintf(
						/* translators: 1: PHP function name, 2: Version number. */
						__( 'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s with no alternative available.' ),
						$function_name,
						$version
					);
				}
			} else {
				if ( $message ) {
					$message = sprintf(
						'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s! %3$s',
						$function_name,
						$version,
						$message
					);
				} else {
					$message = sprintf(
						'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s with no alternative available.',
						$function_name,
						$version
					);
				}
			}
	
			wp_trigger_error( '', $message, E_USER_DEPRECATED );
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_doing_it_wrong' ) ) :
	function _doing_it_wrong( $function_name, $message, $version ) {
	
		/**
		 * Fires when the given function is being used incorrectly.
		 *
		 * @since 3.1.0
		 *
		 * @param string $function_name The function that was called.
		 * @param string $message       A message explaining what has been done incorrectly.
		 * @param string $version       The version of WordPress where the message was added.
		 */
		do_action( 'doing_it_wrong_run', $function_name, $message, $version );
	
		/**
		 * Filters whether to trigger an error for _doing_it_wrong() calls.
		 *
		 * @since 3.1.0
		 * @since 5.1.0 Added the $function_name, $message and $version parameters.
		 *
		 * @param bool   $trigger       Whether to trigger the error for _doing_it_wrong() calls. Default true.
		 * @param string $function_name The function that was called.
		 * @param string $message       A message explaining what has been done incorrectly.
		 * @param string $version       The version of WordPress where the message was added.
		 */
		if ( WP_DEBUG && apply_filters( 'doing_it_wrong_trigger_error', true, $function_name, $message, $version ) ) {
			if ( function_exists( '__' ) ) {
				if ( $version ) {
					/* translators: %s: Version number. */
					$version = sprintf( __( '(This message was added in version %s.)' ), $version );
				}
	
				$message .= ' ' . sprintf(
					/* translators: %s: Documentation URL. */
					__( 'Please see <a href="%s">Debugging in WordPress</a> for more information.' ),
					__( 'https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/' )
				);
	
				$message = sprintf(
					/* translators: Developer debugging message. 1: PHP function name, 2: Explanatory message, 3: WordPress version number. */
					__( 'Function %1$s was called <strong>incorrectly</strong>. %2$s %3$s' ),
					$function_name,
					$message,
					$version
				);
			} else {
				if ( $version ) {
					$version = sprintf( '(This message was added in version %s.)', $version );
				}
	
				$message .= sprintf(
					' Please see <a href="%s">Debugging in WordPress</a> for more information.',
					'https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/'
				);
	
				$message = sprintf(
					'Function %1$s was called <strong>incorrectly</strong>. %2$s %3$s',
					$function_name,
					$message,
					$version
				);
			}
	
			wp_trigger_error( '', $message );
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_trigger_error' ) ) :
	function wp_trigger_error( $function_name, $message, $error_level = E_USER_NOTICE ) {
	
		// Bail out if WP_DEBUG is not turned on.
		if ( ! WP_DEBUG ) {
			return;
		}
	
		/**
		 * Fires when the given function triggers a user-level error/warning/notice/deprecation message.
		 *
		 * Can be used for debug backtracking.
		 *
		 * @since 6.4.0
		 *
		 * @param string $function_name The function that was called.
		 * @param string $message       A message explaining what has been done incorrectly.
		 * @param int    $error_level   The designated error type for this error.
		 */
		do_action( 'wp_trigger_error_run', $function_name, $message, $error_level );
	
		if ( ! empty( $function_name ) ) {
			$message = sprintf( '%s(): %s', $function_name, $message );
		}
	
		$message = wp_kses(
			$message,
			array(
				'a'      => array( 'href' => true ),
				'br'     => array(),
				'code'   => array(),
				'em'     => array(),
				'strong' => array(),
			),
			array( 'http', 'https' )
		);
	
		if ( E_USER_ERROR === $error_level ) {
			throw new WP_Exception( $message );
		}
	
		trigger_error( $message, $error_level );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'validate_file' ) ) :
	function validate_file( $file, $allowed_files = array() ) {
		if ( ! is_scalar( $file ) || '' === $file ) {
			return 0;
		}
	
		// Normalize path for Windows servers.
		$file = wp_normalize_path( $file );
		// Normalize path for $allowed_files as well so it's an apples to apples comparison.
		$allowed_files = array_map( 'wp_normalize_path', $allowed_files );
	
		// `../` on its own is not allowed:
		if ( '../' === $file ) {
			return 1;
		}
	
		// More than one occurrence of `../` is not allowed:
		if ( preg_match_all( '#\.\./#', $file, $matches, PREG_SET_ORDER ) && ( count( $matches ) > 1 ) ) {
			return 1;
		}
	
		// `../` which does not occur at the end of the path is not allowed:
		if ( str_contains( $file, '../' ) && '../' !== mb_substr( $file, -3, 3 ) ) {
			return 1;
		}
	
		// Files not in the allowed file list are not allowed:
		if ( ! empty( $allowed_files ) && ! in_array( $file, $allowed_files, true ) ) {
			return 3;
		}
	
		// Absolute Windows drive paths are not allowed:
		if ( ':' === substr( $file, 1, 1 ) ) {
			return 2;
		}
	
		return 0;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'force_ssl_admin' ) ) :
	function force_ssl_admin( $force = null ) {
		static $forced = false;
	
		if ( ! is_null( $force ) ) {
			$old_forced = $forced;
			$forced     = (bool) $force;
			return $old_forced;
		}
	
		return $forced;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_cleanup_header_comment' ) ) :
	function _cleanup_header_comment( $str ) {
		return trim( preg_replace( '/\s*(?:\*\/|\?>).*/', '', $str ) );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'get_file_data' ) ) :
	function get_file_data( $file, $default_headers, $context = '' ) {
		// Pull only the first 8 KB of the file in.
		$file_data = file_get_contents( $file, false, null, 0, 8 * KB_IN_BYTES );
	
		if ( false === $file_data ) {
			$file_data = '';
		}
	
		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );
	
		/**
		 * Filters extra file headers by context.
		 *
		 * The dynamic portion of the hook name, `$context`, refers to
		 * the context where extra headers might be loaded.
		 *
		 * @since 2.9.0
		 *
		 * @param array $extra_context_headers Empty array by default.
		 */
		$extra_headers = $context ? apply_filters( "extra_{$context}_headers", array() ) : array();
		if ( $extra_headers ) {
			$extra_headers = array_combine( $extra_headers, $extra_headers ); // Keys equal values.
			$all_headers   = array_merge( $extra_headers, (array) $default_headers );
		} else {
			$all_headers = $default_headers;
		}
	
		foreach ( $all_headers as $field => $regex ) {
			if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] ) {
				$all_headers[ $field ] = _cleanup_header_comment( $match[1] );
			} else {
				$all_headers[ $field ] = '';
			}
		}
	
		return $all_headers;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '__return_true' ) ) :
	function __return_true() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore
		return true;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '__return_false' ) ) :
	function __return_false() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore
		return false;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '__return_zero' ) ) :
	function __return_zero() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore
		return 0;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '__return_empty_array' ) ) :
	function __return_empty_array() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore
		return array();
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '__return_null' ) ) :
	function __return_null() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore
		return null;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '__return_empty_string' ) ) :
	function __return_empty_string() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore
		return '';
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_find_hierarchy_loop' ) ) :
	function wp_find_hierarchy_loop( $callback, $start, $start_parent, $callback_args = array() ) {
		$override = is_null( $start_parent ) ? array() : array( $start => $start_parent );
	
		$arbitrary_loop_member = wp_find_hierarchy_loop_tortoise_hare( $callback, $start, $override, $callback_args );
		if ( ! $arbitrary_loop_member ) {
			return array();
		}
	
		return wp_find_hierarchy_loop_tortoise_hare( $callback, $arbitrary_loop_member, $override, $callback_args, true );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_find_hierarchy_loop_tortoise_hare' ) ) :
	function wp_find_hierarchy_loop_tortoise_hare( $callback, $start, $override = array(), $callback_args = array(), $_return_loop = false ) {
		$tortoise        = $start;
		$hare            = $start;
		$evanescent_hare = $start;
		$return          = array();
	
		// Set evanescent_hare to one past hare. Increment hare two steps.
		while (
			$tortoise
		&&
			( $evanescent_hare = isset( $override[ $hare ] ) ? $override[ $hare ] : call_user_func_array( $callback, array_merge( array( $hare ), $callback_args ) ) )
		&&
			( $hare = isset( $override[ $evanescent_hare ] ) ? $override[ $evanescent_hare ] : call_user_func_array( $callback, array_merge( array( $evanescent_hare ), $callback_args ) ) )
		) {
			if ( $_return_loop ) {
				$return[ $tortoise ]        = true;
				$return[ $evanescent_hare ] = true;
				$return[ $hare ]            = true;
			}
	
			// Tortoise got lapped - must be a loop.
			if ( $tortoise === $evanescent_hare || $tortoise === $hare ) {
				return $_return_loop ? $return : $tortoise;
			}
	
			// Increment tortoise by one step.
			$tortoise = isset( $override[ $tortoise ] ) ? $override[ $tortoise ] : call_user_func_array( $callback, array_merge( array( $tortoise ), $callback_args ) );
		}
	
		return false;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_allowed_protocols' ) ) :
	function wp_allowed_protocols() {
		static $protocols = array();
	
		if ( empty( $protocols ) ) {
			$protocols = array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'irc6', 'ircs', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'sms', 'svn', 'tel', 'fax', 'xmpp', 'webcal', 'urn' );
		}
	
		if ( ! did_action( 'wp_loaded' ) ) {
			/**
			 * Filters the list of protocols allowed in HTML attributes.
			 *
			 * @since 3.0.0
			 *
			 * @param string[] $protocols Array of allowed protocols e.g. 'http', 'ftp', 'tel', and more.
			 */
			$protocols = array_unique( (array) apply_filters( 'kses_allowed_protocols', $protocols ) );
		}
	
		return $protocols;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_debug_backtrace_summary' ) ) :
	function wp_debug_backtrace_summary( $ignore_class = null, $skip_frames = 0, $pretty = true ) {
		static $truncate_paths;
	
		$trace       = debug_backtrace( false );
		$caller      = array();
		$check_class = ! is_null( $ignore_class );
		++$skip_frames; // Skip this function.
	
		if ( ! isset( $truncate_paths ) ) {
			$truncate_paths = array(
				wp_normalize_path( WP_CONTENT_DIR ),
				wp_normalize_path( ABSPATH ),
			);
		}
	
		foreach ( $trace as $call ) {
			if ( $skip_frames > 0 ) {
				--$skip_frames;
			} elseif ( isset( $call['class'] ) ) {
				if ( $check_class && $ignore_class === $call['class'] ) {
					continue; // Filter out calls.
				}
	
				$caller[] = "{$call['class']}{$call['type']}{$call['function']}";
			} else {
				if ( in_array( $call['function'], array( 'do_action', 'apply_filters', 'do_action_ref_array', 'apply_filters_ref_array' ), true ) ) {
					$caller[] = "{$call['function']}('{$call['args'][0]}')";
				} elseif ( in_array( $call['function'], array( 'include', 'include_once', 'require', 'require_once' ), true ) ) {
					$filename = isset( $call['args'][0] ) ? $call['args'][0] : '';
					$caller[] = $call['function'] . "('" . str_replace( $truncate_paths, '', wp_normalize_path( $filename ) ) . "')";
				} else {
					$caller[] = $call['function'];
				}
			}
		}
		if ( $pretty ) {
			return implode( ', ', array_reverse( $caller ) );
		} else {
			return $caller;
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_is_stream' ) ) :
	function wp_is_stream( $path ) {
		$scheme_separator = strpos( $path, '://' );
	
		if ( false === $scheme_separator ) {
			// $path isn't a stream.
			return false;
		}
	
		$stream = substr( $path, 0, $scheme_separator );
	
		return in_array( $stream, stream_get_wrappers(), true );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_checkdate' ) ) :
	function wp_checkdate( $month, $day, $year, $source_date ) {
		/**
		 * Filters whether the given date is valid for the Gregorian calendar.
		 *
		 * @since 3.5.0
		 *
		 * @param bool   $checkdate   Whether the given date is valid.
		 * @param string $source_date Date to check.
		 */
		return apply_filters( 'wp_checkdate', checkdate( $month, $day, $year ), $source_date );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'is_utf8_charset' ) ) :
	function is_utf8_charset( $blog_charset = null ) {
		return _is_utf8_charset( $blog_charset ?? $GLOBALS['stub_wp_options']->blog_charset );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( '_canonical_charset' ) ) :
	function _canonical_charset( $charset ) {
		if ( is_utf8_charset( $charset ) ) {
			return 'UTF-8';
		}
	
		/*
		 * Normalize the ISO-8859-1 family of languages.
		 *
		 * This is not required for htmlspecialchars(), as it properly recognizes all of
		 * the input character sets that here are transformed into "ISO-8859-1".
		 *
		 * @todo Should this entire check be removed since it's not required for the stated purpose?
		 * @todo Should WordPress transform other potential charset equivalents, such as "latin1"?
		 */
		if (
			( 0 === strcasecmp( 'iso-8859-1', $charset ) ) ||
			( 0 === strcasecmp( 'iso8859-1', $charset ) )
		) {
			return 'ISO-8859-1';
		}
	
		return $charset;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'mbstring_binary_safe_encoding' ) ) :
	function mbstring_binary_safe_encoding( $reset = false ) {
		static $encodings  = array();
		static $overloaded = null;
	
		if ( is_null( $overloaded ) ) {
			if ( function_exists( 'mb_internal_encoding' )
				&& ( (int) ini_get( 'mbstring.func_overload' ) & 2 ) // phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives.mbstring_func_overloadDeprecated
			) {
				$overloaded = true;
			} else {
				$overloaded = false;
			}
		}
	
		if ( false === $overloaded ) {
			return;
		}
	
		if ( ! $reset ) {
			$encoding = mb_internal_encoding();
			array_push( $encodings, $encoding );
			mb_internal_encoding( 'ISO-8859-1' );
		}
	
		if ( $reset && $encodings ) {
			$encoding = array_pop( $encodings );
			mb_internal_encoding( $encoding );
		}
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'reset_mbstring_encoding' ) ) :
	function reset_mbstring_encoding() {
		mbstring_binary_safe_encoding( true );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_validate_boolean' ) ) :
	function wp_validate_boolean( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}
	
		if ( is_string( $value ) && 'false' === strtolower( $value ) ) {
			return false;
		}
	
		return (bool) $value;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_generate_uuid4' ) ) :
	function wp_generate_uuid4() {
		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff )
		);
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_is_uuid' ) ) :
	function wp_is_uuid( $uuid, $version = null ) {
	
		if ( ! is_string( $uuid ) ) {
			return false;
		}
	
		if ( is_numeric( $version ) ) {
			if ( 4 !== (int) $version ) {
				_doing_it_wrong( __FUNCTION__, __( 'Only UUID V4 is supported at this time.' ), '4.9.0' );
				return false;
			}
			$regex = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
		} else {
			$regex = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';
		}
	
		return (bool) preg_match( $regex, $uuid );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_unique_id' ) ) :
	function wp_unique_id( $prefix = '' ) {
		static $id_counter = 0;
		return $prefix . (string) ++$id_counter;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_unique_prefixed_id' ) ) :
	function wp_unique_prefixed_id( $prefix = '' ) {
		static $id_counters = array();
	
		if ( ! is_string( $prefix ) ) {
			wp_trigger_error(
				__FUNCTION__,
				sprintf( 'The prefix must be a string. "%s" data type given.', gettype( $prefix ) )
			);
			$prefix = '';
		}
	
		if ( ! isset( $id_counters[ $prefix ] ) ) {
			$id_counters[ $prefix ] = 0;
		}
	
		$id = ++$id_counters[ $prefix ];
	
		return $prefix . (string) $id;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_privacy_anonymize_ip' ) ) :
	function wp_privacy_anonymize_ip( $ip_addr, $ipv6_fallback = false ) {
		if ( empty( $ip_addr ) ) {
			return '0.0.0.0';
		}
	
		// Detect what kind of IP address this is.
		$ip_prefix = '';
		$is_ipv6   = substr_count( $ip_addr, ':' ) > 1;
		$is_ipv4   = ( 3 === substr_count( $ip_addr, '.' ) );
	
		if ( $is_ipv6 && $is_ipv4 ) {
			// IPv6 compatibility mode, temporarily strip the IPv6 part, and treat it like IPv4.
			$ip_prefix = '::ffff:';
			$ip_addr   = preg_replace( '/^\[?[0-9a-f:]*:/i', '', $ip_addr );
			$ip_addr   = str_replace( ']', '', $ip_addr );
			$is_ipv6   = false;
		}
	
		if ( $is_ipv6 ) {
			// IPv6 addresses will always be enclosed in [] if there's a port.
			$left_bracket  = strpos( $ip_addr, '[' );
			$right_bracket = strpos( $ip_addr, ']' );
			$percent       = strpos( $ip_addr, '%' );
			$netmask       = 'ffff:ffff:ffff:ffff:0000:0000:0000:0000';
	
			// Strip the port (and [] from IPv6 addresses), if they exist.
			if ( false !== $left_bracket && false !== $right_bracket ) {
				$ip_addr = substr( $ip_addr, $left_bracket + 1, $right_bracket - $left_bracket - 1 );
			} elseif ( false !== $left_bracket || false !== $right_bracket ) {
				// The IP has one bracket, but not both, so it's malformed.
				return '::';
			}
	
			// Strip the reachability scope.
			if ( false !== $percent ) {
				$ip_addr = substr( $ip_addr, 0, $percent );
			}
	
			// No invalid characters should be left.
			if ( preg_match( '/[^0-9a-f:]/i', $ip_addr ) ) {
				return '::';
			}
	
			// Partially anonymize the IP by reducing it to the corresponding network ID.
			if ( function_exists( 'inet_pton' ) && function_exists( 'inet_ntop' ) ) {
				$ip_addr = inet_ntop( inet_pton( $ip_addr ) & inet_pton( $netmask ) );
				if ( false === $ip_addr ) {
					return '::';
				}
			} elseif ( ! $ipv6_fallback ) {
				return '::';
			}
		} elseif ( $is_ipv4 ) {
			// Strip any port and partially anonymize the IP.
			$last_octet_position = strrpos( $ip_addr, '.' );
			$ip_addr             = substr( $ip_addr, 0, $last_octet_position ) . '.0';
		} else {
			return '0.0.0.0';
		}
	
		// Restore the IPv6 prefix to compatibility mode addresses.
		return $ip_prefix . $ip_addr;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_privacy_anonymize_data' ) ) :
	function wp_privacy_anonymize_data( $type, $data = '' ) {
	
		switch ( $type ) {
			case 'email':
				$anonymous = 'deleted@site.invalid';
				break;
			case 'url':
				$anonymous = 'https://site.invalid';
				break;
			case 'ip':
				$anonymous = wp_privacy_anonymize_ip( $data );
				break;
			case 'date':
				$anonymous = '0000-00-00 00:00:00';
				break;
			case 'text':
				/* translators: Deleted text. */
				$anonymous = __( '[deleted]' );
				break;
			case 'longtext':
				/* translators: Deleted long text. */
				$anonymous = __( 'This content was deleted by the author.' );
				break;
			default:
				$anonymous = '';
				break;
		}
	
		/**
		 * Filters the anonymous data for each type.
		 *
		 * @since 4.9.6
		 *
		 * @param string $anonymous Anonymized data.
		 * @param string $type      Type of the data.
		 * @param string $data      Original data.
		 */
		return apply_filters( 'wp_privacy_anonymize_data', $anonymous, $type, $data );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_get_wp_version' ) ) :
	function wp_get_wp_version() {
		static $wp_version;
	
		if ( ! isset( $wp_version ) ) {
			require ABSPATH . WPINC . '/version.php';
		}
	
		return $wp_version;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_fuzzy_number_match' ) ) :
	function wp_fuzzy_number_match( $expected, $actual, $precision = 1 ) {
		return abs( (float) $expected - (float) $actual ) <= $precision;
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_is_heic_image_mime_type' ) ) :
	function wp_is_heic_image_mime_type( $mime_type ) {
		$heic_mime_types = array(
			'image/heic',
			'image/heif',
			'image/heic-sequence',
			'image/heif-sequence',
		);
	
		return in_array( $mime_type, $heic_mime_types, true );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_fast_hash' ) ) :
	function wp_fast_hash(
		#[\SensitiveParameter]
		string $message
	): string {
		$hashed = sodium_crypto_generichash( $message, 'wp_fast_hash_6.8+', 30 );
		return '$generic$' . sodium_bin2base64( $hashed, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_verify_fast_hash' ) ) :
	function wp_verify_fast_hash(
		#[\SensitiveParameter]
		string $message,
		string $hash
	): bool {
		if ( ! str_starts_with( $hash, '$generic$' ) ) {
			// Back-compat for old phpass hashes.
			require_once ABSPATH . WPINC . '/class-phpass.php';
			return ( new PasswordHash( 8, true ) )->CheckPassword( $message, $hash );
		}
	
		return hash_equals( $hash, wp_fast_hash( $message ) );
	}
endif;

// wp-includes/functions.php (WP 6.8.3)
if( ! function_exists( 'wp_unique_id_from_values' ) ) :
	function wp_unique_id_from_values( array $data, string $prefix = '' ): string {
		if ( empty( $data ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: %s: parameter name. */
					__( 'The %s argument must not be empty.' ),
					'$data'
				),
				'6.8.0'
			);
		}
		$serialized = wp_json_encode( $data );
		$hash       = substr( md5( $serialized ), 0, 8 );
		return $prefix . $hash;
	}
endif;

