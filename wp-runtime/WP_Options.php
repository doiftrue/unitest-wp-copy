<?php

namespace Unitest_WP_Copy;

/**
 * In-memory WordPress option state for the test runtime.
 */
final class WP_Options {

	private const DEFAULT_OPTIONS = [
		'home'                        => 'https://wp.test',
		'siteurl'                     => 'https://wp.test',
		'gmt_offset'                  => 0,
		'timezone_string'             => 'UTC',
		'start_of_week'               => 1,
		'language'                    => 'en-US',
		'blogname'                    => 'Unitest WP Copy',
		'blogdescription'             => 'unitest-wp-copy runtime',
		'blog_public'                 => '1',
		'admin_email'                 => 'admin@wp.test',
		'stylesheet'                  => 'wp-test-stylesheet',
		'template'                    => 'wp-test-template',
		'use_smilies'                 => true,
		'use_balanceTags'             => true,
		'permalink_structure'         => '/%postname%/',
		'show_on_front'               => 'posts',
		'page_on_front'               => 0,
		'page_for_posts'              => 0,
		'site_icon'                   => 0,
		'WPLANG'                      => '',
		'blog_charset'                => 'UTF-8',
		'html_type'                   => 'text/html',
		'thumbnail_size_w'            => 150,
		'thumbnail_size_h'            => 150,
		'thumbnail_crop'              => true,
		'medium_size_w'               => 300,
		'medium_size_h'               => 300,
		'medium_crop'                 => false,
		'medium_large_size_w'         => 768,
		'medium_large_size_h'         => 0,
		'medium_large_crop'           => false,
		'large_size_w'                => 1024,
		'large_size_h'                => 1024,
		'large_crop'                  => false,
		'banned_email_domains'        => [],
		'upload_filetypes'            => 'jpg jpeg png gif',
		'upload_space_check_disabled' => false,
		'fileupload_maxk'             => 1500,
		'registration'                => 'none',
		'blog_upload_space'           => 100,
		'https_migration_required'    => false,
	];

	private const DEFAULT_SITE_OPTIONS = [
		'banned_email_domains'        => [],
		'upload_filetypes'            => 'jpg jpeg png gif',
		'upload_space_check_disabled' => false,
		'fileupload_maxk'             => 1500,
		'registration'                => 'none',
		'blog_upload_space'           => 100,
	];

	private static array $options = [];
	private static array $site_options = [];
	private static array $saved_states = [];
	private static bool $booted = false;

	public static function set( string $name, mixed $value ): void {
		self::$options[ $name ] = $value;
	}

	public static function set_site( string $name, mixed $value ): void {
		self::$site_options[ $name ] = $value;
	}

	public static function save_state(): void {
		self::assert_booted();

		try {
			$snapshot = serialize( [ self::$options, self::$site_options ] );
		}
		catch ( \Throwable $e ) {
			throw new \LogicException( 'Option state cannot be saved.', 0, $e );
		}

		self::$saved_states[] = $snapshot;
	}

	public static function restore_state(): void {
		self::assert_booted();

		if ( ! self::$saved_states ) {
			throw new \LogicException( 'There is no saved option state to restore.' );
		}

		$snapshot = array_pop( self::$saved_states );
		[ self::$options, self::$site_options ] = unserialize( $snapshot, [ 'allowed_classes' => true ] );
	}

	/** @internal */
	public static function boot(): void {
		if ( self::$booted ) {
			return;
		}

		self::$options += self::DEFAULT_OPTIONS;
		self::$site_options += [
			'siteurl' => self::$options['siteurl'],
			'WPLANG'  => self::$options['WPLANG'],
		] + self::DEFAULT_SITE_OPTIONS;
		self::$booted = true;
	}

	/** @internal */
	public static function has( string $name ): bool {
		return array_key_exists( $name, self::$options );
	}

	/** @internal */
	public static function get( string $name ): mixed {
		return self::$options[ $name ];
	}

	/** @internal */
	public static function has_site( string $name ): bool {
		return array_key_exists( $name, self::$site_options );
	}

	/** @internal */
	public static function get_site( string $name ): mixed {
		return self::$site_options[ $name ];
	}

	private static function assert_booted(): void {
		if ( ! self::$booted ) {
			throw new \LogicException( 'Boot the runtime before saving or restoring option state.' );
		}
	}

}
