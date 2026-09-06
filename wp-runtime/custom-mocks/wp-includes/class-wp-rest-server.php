<?php
/**
 * In-memory WordPress REST server adapter for route dispatch in unit tests.
 */

namespace Unitest_WP_Copy;

use LogicException;
use WP_REST_Response;

class WP_REST_Server__Runtime {

	use WP_REST_Server__Copied_Methods;

	public const READABLE = 'GET';
	public const CREATABLE = 'POST';
	public const EDITABLE = 'POST, PUT, PATCH';
	public const DELETABLE = 'DELETE';
	public const ALLMETHODS = 'GET, POST, PUT, PATCH, DELETE';

	public array $sent_headers = [];
	public ?int $sent_status = null;

	protected $namespaces = [];
	protected $endpoints = [];
	protected $route_options = [];
	protected $embed_cache = [];
	protected $dispatching_requests = [];

	/**
	 * Runtime-adapted root index without theme, media, or post-model enrichment.
	 */
	public function get_index( $request ) {
		$available = [
			'name'            => get_option( 'blogname' ),
			'description'     => get_option( 'blogdescription' ),
			'url'             => get_option( 'siteurl' ),
			'home'            => home_url(),
			'gmt_offset'      => get_option( 'gmt_offset' ),
			'timezone_string' => get_option( 'timezone_string' ),
		];

		if ( version_compare( $GLOBALS['wp_version'], '6.7', '>=' ) ) {
			$available['page_for_posts'] = (int) get_option( 'page_for_posts' );
			$available['page_on_front']  = (int) get_option( 'page_on_front' );
			$available['show_on_front']  = get_option( 'show_on_front' );
		}

		$available['namespaces']     = array_keys( $this->namespaces );
		$available['authentication'] = [];
		$available['routes']         = $this->get_data_for_routes( $this->get_routes(), $request['context'] );

		$response = new WP_REST_Response( $available );
		$fields   = wp_parse_list( $request['_fields'] ?? '' );

		if ( empty( $fields ) ) {
			$fields[] = '_links';
		}

		if ( $request->has_param( '_embed' ) ) {
			$fields[] = '_embedded';
		}

		if ( rest_is_field_included( '_links', $fields ) || rest_is_field_included( '_embedded', $fields ) ) {
			$response->add_link( 'help', 'https://developer.wordpress.org/rest-api/' );
		}

		return apply_filters( 'rest_index', $response, $request );
	}

	/** Live HTTP serving is intentionally unavailable in this runtime. */
	public function serve_request( $path = null ) {
		throw new LogicException( 'Live REST serving is out of runtime scope; use rest_do_request().' );
	}

	/** Records the status instead of sending an HTTP status line. */
	protected function set_status( $code ) {
		$this->sent_status = (int) $code;
	}

	/** Records a header instead of sending it to the PHP output layer. */
	public function send_header( $key, $value ) {
		$this->sent_headers[ $key ] = preg_replace( '/\s+/', ' ', $value );
	}

	/** Removes a previously recorded header. */
	public function remove_header( $key ) {
		unset( $this->sent_headers[ $key ] );
	}

	/** The isolated runtime has no live request body stream. */
	public static function get_raw_data() {
		return '';
	}
}

if ( ! class_exists( '\WP_REST_Server', false ) ) {
	class_alias( WP_REST_Server__Runtime::class, 'WP_REST_Server' );
}
