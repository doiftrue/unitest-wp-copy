<?php

// ------------------auto-generated---------------------

namespace Unitest_WP_Copy;

use \WP_Error;
use \WP_REST_Request;
use \WP_REST_Response;

// wp-includes/rest-api/class-wp-rest-server.php (WP 6.5.10)
trait WP_REST_Server__Copied_Methods {

	public function __construct() {
		$this->endpoints = array(
			// Meta endpoints.
			'/'         => array(
				'callback' => array( $this, 'get_index' ),
				'methods'  => 'GET',
				'args'     => array(
					'context' => array(
						'default' => 'view',
					),
				),
			),
			'/batch/v1' => array(
				'callback' => array( $this, 'serve_batch_request_v1' ),
				'methods'  => 'POST',
				'args'     => array(
					'validation' => array(
						'type'    => 'string',
						'enum'    => array( 'require-all-validate', 'normal' ),
						'default' => 'normal',
					),
					'requests'   => array(
						'required' => true,
						'type'     => 'array',
						'maxItems' => $this->get_max_batch_size(),
						'items'    => array(
							'type'       => 'object',
							'properties' => array(
								'method'  => array(
									'type'    => 'string',
									'enum'    => array( 'POST', 'PUT', 'PATCH', 'DELETE' ),
									'default' => 'POST',
								),
								'path'    => array(
									'type'     => 'string',
									'required' => true,
								),
								'body'    => array(
									'type'                 => 'object',
									'properties'           => array(),
									'additionalProperties' => true,
								),
								'headers' => array(
									'type'                 => 'object',
									'properties'           => array(),
									'additionalProperties' => array(
										'type'  => array( 'string', 'array' ),
										'items' => array(
											'type' => 'string',
										),
									),
								),
							),
						),
					),
				),
			),
		);
	}

	public function check_authentication() {
		/**
		 * Filters REST API authentication errors.
		 *
		 * This is used to pass a WP_Error from an authentication method back to
		 * the API.
		 *
		 * Authentication methods should check first if they're being used, as
		 * multiple authentication methods can be enabled on a site (cookies,
		 * HTTP basic auth, OAuth). If the authentication method hooked in is
		 * not actually being attempted, null should be returned to indicate
		 * another authentication method should check instead. Similarly,
		 * callbacks should ensure the value is `null` before checking for
		 * errors.
		 *
		 * A WP_Error instance can be returned if an error occurs, and this should
		 * match the format used by API methods internally (that is, the `status`
		 * data should be used). A callback can return `true` to indicate that
		 * the authentication method was used, and it succeeded.
		 *
		 * @since 4.4.0
		 *
		 * @param WP_Error|null|true $errors WP_Error if authentication error, null if authentication
		 *                                   method wasn't used, true if authentication succeeded.
		 */
		return apply_filters( 'rest_authentication_errors', null );
	}

	protected function error_to_response( $error ) {
		return rest_convert_error_to_response( $error );
	}

	public function response_to_data( $response, $embed ) {
		$data  = $response->get_data();
		$links = self::get_compact_response_links( $response );

		if ( ! empty( $links ) ) {
			// Convert links to part of the data.
			$data['_links'] = $links;
		}

		if ( $embed ) {
			$this->embed_cache = array();
			// Determine if this is a numeric array.
			if ( wp_is_numeric_array( $data ) ) {
				foreach ( $data as $key => $item ) {
					$data[ $key ] = $this->embed_links( $item, $embed );
				}
			} else {
				$data = $this->embed_links( $data, $embed );
			}
			$this->embed_cache = array();
		}

		return $data;
	}

	public static function get_response_links( $response ) {
		$links = $response->get_links();

		if ( empty( $links ) ) {
			return array();
		}

		// Convert links to part of the data.
		$data = array();
		foreach ( $links as $rel => $items ) {
			$data[ $rel ] = array();

			foreach ( $items as $item ) {
				$attributes         = $item['attributes'];
				$attributes['href'] = $item['href'];
				$data[ $rel ][]     = $attributes;
			}
		}

		return $data;
	}

	public static function get_compact_response_links( $response ) {
		$links = self::get_response_links( $response );

		if ( empty( $links ) ) {
			return array();
		}

		$curies      = $response->get_curies();
		$used_curies = array();

		foreach ( $links as $rel => $items ) {

			// Convert $rel URIs to their compact versions if they exist.
			foreach ( $curies as $curie ) {
				$href_prefix = substr( $curie['href'], 0, strpos( $curie['href'], '{rel}' ) );
				if ( ! str_starts_with( $rel, $href_prefix ) ) {
					continue;
				}

				// Relation now changes from '$uri' to '$curie:$relation'.
				$rel_regex = str_replace( '\{rel\}', '(.+)', preg_quote( $curie['href'], '!' ) );
				preg_match( '!' . $rel_regex . '!', $rel, $matches );
				if ( $matches ) {
					$new_rel                       = $curie['name'] . ':' . $matches[1];
					$used_curies[ $curie['name'] ] = $curie;
					$links[ $new_rel ]             = $items;
					unset( $links[ $rel ] );
					break;
				}
			}
		}

		// Push the curies onto the start of the links array.
		if ( $used_curies ) {
			$links['curies'] = array_values( $used_curies );
		}

		return $links;
	}

	protected function embed_links( $data, $embed = true ) {
		if ( empty( $data['_links'] ) ) {
			return $data;
		}

		$embedded = array();

		foreach ( $data['_links'] as $rel => $links ) {
			/*
			 * If a list of relations was specified, and the link relation
			 * is not in the list of allowed relations, don't process the link.
			 */
			if ( is_array( $embed ) && ! in_array( $rel, $embed, true ) ) {
				continue;
			}

			$embeds = array();

			foreach ( $links as $item ) {
				// Determine if the link is embeddable.
				if ( empty( $item['embeddable'] ) ) {
					// Ensure we keep the same order.
					$embeds[] = array();
					continue;
				}

				if ( ! array_key_exists( $item['href'], $this->embed_cache ) ) {
					// Run through our internal routing and serve.
					$request = WP_REST_Request::from_url( $item['href'] );
					if ( ! $request ) {
						$embeds[] = array();
						continue;
					}

					// Embedded resources get passed context=embed.
					if ( empty( $request['context'] ) ) {
						$request['context'] = 'embed';
					}

					if ( empty( $request['per_page'] ) ) {
						$matched = $this->match_request_to_handler( $request );
						if ( ! is_wp_error( $matched ) && isset( $matched[1]['args']['per_page']['maximum'] ) ) {
							$request['per_page'] = (int) $matched[1]['args']['per_page']['maximum'];
						}
					}

					$response = $this->dispatch( $request );

					/** This filter is documented in wp-includes/rest-api/class-wp-rest-server.php */
					$response = apply_filters( 'rest_post_dispatch', rest_ensure_response( $response ), $this, $request );

					$this->embed_cache[ $item['href'] ] = $this->response_to_data( $response, false );
				}

				$embeds[] = $this->embed_cache[ $item['href'] ];
			}

			// Determine if any real links were found.
			$has_links = count( array_filter( $embeds ) );

			if ( $has_links ) {
				$embedded[ $rel ] = $embeds;
			}
		}

		if ( ! empty( $embedded ) ) {
			$data['_embedded'] = $embedded;
		}

		return $data;
	}

	public function envelope_response( $response, $embed ) {
		$envelope = array(
			'body'    => $this->response_to_data( $response, $embed ),
			'status'  => $response->get_status(),
			'headers' => $response->get_headers(),
		);

		/**
		 * Filters the enveloped form of a REST API response.
		 *
		 * @since 4.4.0
		 *
		 * @param array            $envelope {
		 *     Envelope data.
		 *
		 *     @type array $body    Response data.
		 *     @type int   $status  The 3-digit HTTP status code.
		 *     @type array $headers Map of header name to header value.
		 * }
		 * @param WP_REST_Response $response Original response data.
		 */
		$envelope = apply_filters( 'rest_envelope_response', $envelope, $response );

		// Ensure it's still a response and return.
		return rest_ensure_response( $envelope );
	}

	public function register_route( $route_namespace, $route, $route_args, $override = false ) {
		if ( ! isset( $this->namespaces[ $route_namespace ] ) ) {
			$this->namespaces[ $route_namespace ] = array();

			$this->register_route(
				$route_namespace,
				'/' . $route_namespace,
				array(
					array(
						'methods'  => self::READABLE,
						'callback' => array( $this, 'get_namespace_index' ),
						'args'     => array(
							'namespace' => array(
								'default' => $route_namespace,
							),
							'context'   => array(
								'default' => 'view',
							),
						),
					),
				)
			);
		}

		// Associative to avoid double-registration.
		$this->namespaces[ $route_namespace ][ $route ] = true;

		$route_args['namespace'] = $route_namespace;

		if ( $override || empty( $this->endpoints[ $route ] ) ) {
			$this->endpoints[ $route ] = $route_args;
		} else {
			$this->endpoints[ $route ] = array_merge( $this->endpoints[ $route ], $route_args );
		}
	}

	public function get_routes( $route_namespace = '' ) {
		$endpoints = $this->endpoints;

		if ( $route_namespace ) {
			$endpoints = wp_list_filter( $endpoints, array( 'namespace' => $route_namespace ) );
		}

		/**
		 * Filters the array of available REST API endpoints.
		 *
		 * @since 4.4.0
		 *
		 * @param array $endpoints The available endpoints. An array of matching regex patterns, each mapped
		 *                         to an array of callbacks for the endpoint. These take the format
		 *                         `'/path/regex' => array( $callback, $bitmask )` or
		 *                         `'/path/regex' => array( array( $callback, $bitmask ).
		 */
		$endpoints = apply_filters( 'rest_endpoints', $endpoints );

		// Normalize the endpoints.
		$defaults = array(
			'methods'       => '',
			'accept_json'   => false,
			'accept_raw'    => false,
			'show_in_index' => true,
			'args'          => array(),
		);

		foreach ( $endpoints as $route => &$handlers ) {

			if ( isset( $handlers['callback'] ) ) {
				// Single endpoint, add one deeper.
				$handlers = array( $handlers );
			}

			if ( ! isset( $this->route_options[ $route ] ) ) {
				$this->route_options[ $route ] = array();
			}

			foreach ( $handlers as $key => &$handler ) {

				if ( ! is_numeric( $key ) ) {
					// Route option, move it to the options.
					$this->route_options[ $route ][ $key ] = $handler;
					unset( $handlers[ $key ] );
					continue;
				}

				$handler = wp_parse_args( $handler, $defaults );

				// Allow comma-separated HTTP methods.
				if ( is_string( $handler['methods'] ) ) {
					$methods = explode( ',', $handler['methods'] );
				} elseif ( is_array( $handler['methods'] ) ) {
					$methods = $handler['methods'];
				} else {
					$methods = array();
				}

				$handler['methods'] = array();

				foreach ( $methods as $method ) {
					$method                        = strtoupper( trim( $method ) );
					$handler['methods'][ $method ] = true;
				}
			}
		}

		return $endpoints;
	}

	public function get_namespaces() {
		return array_keys( $this->namespaces );
	}

	public function get_route_options( $route ) {
		if ( ! isset( $this->route_options[ $route ] ) ) {
			return null;
		}

		return $this->route_options[ $route ];
	}

	public function dispatch( $request ) {
		$this->dispatching_requests[] = $request;

		/**
		 * Filters the pre-calculated result of a REST API dispatch request.
		 *
		 * Allow hijacking the request before dispatching by returning a non-empty. The returned value
		 * will be used to serve the request instead.
		 *
		 * @since 4.4.0
		 *
		 * @param mixed           $result  Response to replace the requested version with. Can be anything
		 *                                 a normal endpoint can return, or null to not hijack the request.
		 * @param WP_REST_Server  $server  Server instance.
		 * @param WP_REST_Request $request Request used to generate the response.
		 */
		$result = apply_filters( 'rest_pre_dispatch', null, $this, $request );

		if ( ! empty( $result ) ) {

			// Normalize to either WP_Error or WP_REST_Response...
			$result = rest_ensure_response( $result );

			// ...then convert WP_Error across.
			if ( is_wp_error( $result ) ) {
				$result = $this->error_to_response( $result );
			}

			array_pop( $this->dispatching_requests );
			return $result;
		}

		$error   = null;
		$matched = $this->match_request_to_handler( $request );

		if ( is_wp_error( $matched ) ) {
			$response = $this->error_to_response( $matched );
			array_pop( $this->dispatching_requests );
			return $response;
		}

		list( $route, $handler ) = $matched;

		if ( ! is_callable( $handler['callback'] ) ) {
			$error = new WP_Error(
				'rest_invalid_handler',
				__( 'The handler for the route is invalid.' ),
				array( 'status' => 500 )
			);
		}

		if ( ! is_wp_error( $error ) ) {
			$check_required = $request->has_valid_params();
			if ( is_wp_error( $check_required ) ) {
				$error = $check_required;
			} else {
				$check_sanitized = $request->sanitize_params();
				if ( is_wp_error( $check_sanitized ) ) {
					$error = $check_sanitized;
				}
			}
		}

		$response = $this->respond_to_request( $request, $route, $handler, $error );
		array_pop( $this->dispatching_requests );
		return $response;
	}

	public function is_dispatching() {
		return (bool) $this->dispatching_requests;
	}

	protected function match_request_to_handler( $request ) {
		$method = $request->get_method();
		$path   = $request->get_route();

		$with_namespace = array();

		foreach ( $this->get_namespaces() as $namespace ) {
			if ( str_starts_with( trailingslashit( ltrim( $path, '/' ) ), $namespace ) ) {
				$with_namespace[] = $this->get_routes( $namespace );
			}
		}

		if ( $with_namespace ) {
			$routes = array_merge( ...$with_namespace );
		} else {
			$routes = $this->get_routes();
		}

		foreach ( $routes as $route => $handlers ) {
			$match = preg_match( '@^' . $route . '$@i', $path, $matches );

			if ( ! $match ) {
				continue;
			}

			$args = array();

			foreach ( $matches as $param => $value ) {
				if ( ! is_int( $param ) ) {
					$args[ $param ] = $value;
				}
			}

			foreach ( $handlers as $handler ) {
				$callback = $handler['callback'];

				// Fallback to GET method if no HEAD method is registered.
				$checked_method = $method;
				if ( 'HEAD' === $method && empty( $handler['methods']['HEAD'] ) ) {
					$checked_method = 'GET';
				}
				if ( empty( $handler['methods'][ $checked_method ] ) ) {
					continue;
				}

				if ( ! is_callable( $callback ) ) {
					return array( $route, $handler );
				}

				$request->set_url_params( $args );
				$request->set_attributes( $handler );

				$defaults = array();

				foreach ( $handler['args'] as $arg => $options ) {
					if ( isset( $options['default'] ) ) {
						$defaults[ $arg ] = $options['default'];
					}
				}

				$request->set_default_params( $defaults );

				return array( $route, $handler );
			}
		}

		return new WP_Error(
			'rest_no_route',
			__( 'No route was found matching the URL and request method.' ),
			array( 'status' => 404 )
		);
	}

	protected function respond_to_request( $request, $route, $handler, $response ) {
		/**
		 * Filters the response before executing any REST API callbacks.
		 *
		 * Allows plugins to perform additional validation after a
		 * request is initialized and matched to a registered route,
		 * but before it is executed.
		 *
		 * Note that this filter will not be called for requests that
		 * fail to authenticate or match to a registered route.
		 *
		 * @since 4.7.0
		 *
		 * @param WP_REST_Response|WP_HTTP_Response|WP_Error|mixed $response Result to send to the client.
		 *                                                                   Usually a WP_REST_Response or WP_Error.
		 * @param array                                            $handler  Route handler used for the request.
		 * @param WP_REST_Request                                  $request  Request used to generate the response.
		 */
		$response = apply_filters( 'rest_request_before_callbacks', $response, $handler, $request );

		// Check permission specified on the route.
		if ( ! is_wp_error( $response ) && ! empty( $handler['permission_callback'] ) ) {
			$permission = call_user_func( $handler['permission_callback'], $request );

			if ( is_wp_error( $permission ) ) {
				$response = $permission;
			} elseif ( false === $permission || null === $permission ) {
				$response = new WP_Error(
					'rest_forbidden',
					__( 'Sorry, you are not allowed to do that.' ),
					array( 'status' => rest_authorization_required_code() )
				);
			}
		}

		if ( ! is_wp_error( $response ) ) {
			/**
			 * Filters the REST API dispatch request result.
			 *
			 * Allow plugins to override dispatching the request.
			 *
			 * @since 4.4.0
			 * @since 4.5.0 Added `$route` and `$handler` parameters.
			 *
			 * @param mixed           $dispatch_result Dispatch result, will be used if not empty.
			 * @param WP_REST_Request $request         Request used to generate the response.
			 * @param string          $route           Route matched for the request.
			 * @param array           $handler         Route handler used for the request.
			 */
			$dispatch_result = apply_filters( 'rest_dispatch_request', null, $request, $route, $handler );

			// Allow plugins to halt the request via this filter.
			if ( null !== $dispatch_result ) {
				$response = $dispatch_result;
			} else {
				$response = call_user_func( $handler['callback'], $request );
			}
		}

		/**
		 * Filters the response immediately after executing any REST API
		 * callbacks.
		 *
		 * Allows plugins to perform any needed cleanup, for example,
		 * to undo changes made during the {@see 'rest_request_before_callbacks'}
		 * filter.
		 *
		 * Note that this filter will not be called for requests that
		 * fail to authenticate or match to a registered route.
		 *
		 * Note that an endpoint's `permission_callback` can still be
		 * called after this filter - see `rest_send_allow_header()`.
		 *
		 * @since 4.7.0
		 *
		 * @param WP_REST_Response|WP_HTTP_Response|WP_Error|mixed $response Result to send to the client.
		 *                                                                   Usually a WP_REST_Response or WP_Error.
		 * @param array                                            $handler  Route handler used for the request.
		 * @param WP_REST_Request                                  $request  Request used to generate the response.
		 */
		$response = apply_filters( 'rest_request_after_callbacks', $response, $handler, $request );

		if ( is_wp_error( $response ) ) {
			$response = $this->error_to_response( $response );
		} else {
			$response = rest_ensure_response( $response );
		}

		$response->set_matched_route( $route );
		$response->set_matched_handler( $handler );

		return $response;
	}

	public function get_namespace_index( $request ) {
		$namespace = $request['namespace'];

		if ( ! isset( $this->namespaces[ $namespace ] ) ) {
			return new WP_Error(
				'rest_invalid_namespace',
				__( 'The specified namespace could not be found.' ),
				array( 'status' => 404 )
			);
		}

		$routes    = $this->namespaces[ $namespace ];
		$endpoints = array_intersect_key( $this->get_routes(), $routes );

		$data     = array(
			'namespace' => $namespace,
			'routes'    => $this->get_data_for_routes( $endpoints, $request['context'] ),
		);
		$response = rest_ensure_response( $data );

		// Link to the root index.
		$response->add_link( 'up', rest_url( '/' ) );

		/**
		 * Filters the REST API namespace index data.
		 *
		 * This typically is just the route data for the namespace, but you can
		 * add any data you'd like here.
		 *
		 * @since 4.4.0
		 *
		 * @param WP_REST_Response $response Response data.
		 * @param WP_REST_Request  $request  Request data. The namespace is passed as the 'namespace' parameter.
		 */
		return apply_filters( 'rest_namespace_index', $response, $request );
	}

	public function get_data_for_routes( $routes, $context = 'view' ) {
		$available = array();

		// Find the available routes.
		foreach ( $routes as $route => $callbacks ) {
			$data = $this->get_data_for_route( $route, $callbacks, $context );
			if ( empty( $data ) ) {
				continue;
			}

			/**
			 * Filters the publicly-visible data for a single REST API route.
			 *
			 * @since 4.4.0
			 *
			 * @param array $data Publicly-visible data for the route.
			 */
			$available[ $route ] = apply_filters( 'rest_endpoints_description', $data );
		}

		/**
		 * Filters the publicly-visible data for REST API routes.
		 *
		 * This data is exposed on indexes and can be used by clients or
		 * developers to investigate the site and find out how to use it. It
		 * acts as a form of self-documentation.
		 *
		 * @since 4.4.0
		 *
		 * @param array[] $available Route data to expose in indexes, keyed by route.
		 * @param array   $routes    Internal route data as an associative array.
		 */
		return apply_filters( 'rest_route_data', $available, $routes );
	}

	public function get_data_for_route( $route, $callbacks, $context = 'view' ) {
		$data = array(
			'namespace' => '',
			'methods'   => array(),
			'endpoints' => array(),
		);

		$allow_batch = false;

		if ( isset( $this->route_options[ $route ] ) ) {
			$options = $this->route_options[ $route ];

			if ( isset( $options['namespace'] ) ) {
				$data['namespace'] = $options['namespace'];
			}

			$allow_batch = isset( $options['allow_batch'] ) ? $options['allow_batch'] : false;

			if ( isset( $options['schema'] ) && 'help' === $context ) {
				$data['schema'] = call_user_func( $options['schema'] );
			}
		}

		$allowed_schema_keywords = array_flip( rest_get_allowed_schema_keywords() );

		$route = preg_replace( '#\(\?P<(\w+?)>.*?\)#', '{$1}', $route );

		foreach ( $callbacks as $callback ) {
			// Skip to the next route if any callback is hidden.
			if ( empty( $callback['show_in_index'] ) ) {
				continue;
			}

			$data['methods'] = array_merge( $data['methods'], array_keys( $callback['methods'] ) );
			$endpoint_data   = array(
				'methods' => array_keys( $callback['methods'] ),
			);

			$callback_batch = isset( $callback['allow_batch'] ) ? $callback['allow_batch'] : $allow_batch;

			if ( $callback_batch ) {
				$endpoint_data['allow_batch'] = $callback_batch;
			}

			if ( isset( $callback['args'] ) ) {
				$endpoint_data['args'] = array();

				foreach ( $callback['args'] as $key => $opts ) {
					if ( is_string( $opts ) ) {
						$opts = array( $opts => 0 );
					} elseif ( ! is_array( $opts ) ) {
						$opts = array();
					}
					$arg_data             = array_intersect_key( $opts, $allowed_schema_keywords );
					$arg_data['required'] = ! empty( $opts['required'] );

					$endpoint_data['args'][ $key ] = $arg_data;
				}
			}

			$data['endpoints'][] = $endpoint_data;

			// For non-variable routes, generate links.
			if ( ! str_contains( $route, '{' ) ) {
				$data['_links'] = array(
					'self' => array(
						array(
							'href' => rest_url( $route ),
						),
					),
				);
			}
		}

		if ( empty( $data['methods'] ) ) {
			// No methods supported, hide the route.
			return null;
		}

		return $data;
	}

	protected function get_max_batch_size() {
		/**
		 * Filters the maximum number of REST API requests that can be included in a batch.
		 *
		 * @since 5.6.0
		 *
		 * @param int $max_size The maximum size.
		 */
		return apply_filters( 'rest_get_max_batch_size', 25 );
	}

	public function serve_batch_request_v1( WP_REST_Request $batch_request ) {
		$requests = array();

		foreach ( $batch_request['requests'] as $args ) {
			$parsed_url = wp_parse_url( $args['path'] );

			if ( false === $parsed_url ) {
				$requests[] = new WP_Error( 'parse_path_failed', __( 'Could not parse the path.' ), array( 'status' => 400 ) );

				continue;
			}

			$single_request = new WP_REST_Request( isset( $args['method'] ) ? $args['method'] : 'POST', $parsed_url['path'] );

			if ( ! empty( $parsed_url['query'] ) ) {
				$query_args = null; // Satisfy linter.
				wp_parse_str( $parsed_url['query'], $query_args );
				$single_request->set_query_params( $query_args );
			}

			if ( ! empty( $args['body'] ) ) {
				$single_request->set_body_params( $args['body'] );
			}

			if ( ! empty( $args['headers'] ) ) {
				$single_request->set_headers( $args['headers'] );
			}

			$requests[] = $single_request;
		}

		$matches    = array();
		$validation = array();
		$has_error  = false;

		foreach ( $requests as $single_request ) {
			$match     = $this->match_request_to_handler( $single_request );
			$matches[] = $match;
			$error     = null;

			if ( is_wp_error( $match ) ) {
				$error = $match;
			}

			if ( ! $error ) {
				list( $route, $handler ) = $match;

				if ( isset( $handler['allow_batch'] ) ) {
					$allow_batch = $handler['allow_batch'];
				} else {
					$route_options = $this->get_route_options( $route );
					$allow_batch   = isset( $route_options['allow_batch'] ) ? $route_options['allow_batch'] : false;
				}

				if ( ! is_array( $allow_batch ) || empty( $allow_batch['v1'] ) ) {
					$error = new WP_Error(
						'rest_batch_not_allowed',
						__( 'The requested route does not support batch requests.' ),
						array( 'status' => 400 )
					);
				}
			}

			if ( ! $error ) {
				$check_required = $single_request->has_valid_params();
				if ( is_wp_error( $check_required ) ) {
					$error = $check_required;
				}
			}

			if ( ! $error ) {
				$check_sanitized = $single_request->sanitize_params();
				if ( is_wp_error( $check_sanitized ) ) {
					$error = $check_sanitized;
				}
			}

			if ( $error ) {
				$has_error    = true;
				$validation[] = $error;
			} else {
				$validation[] = true;
			}
		}

		$responses = array();

		if ( $has_error && 'require-all-validate' === $batch_request['validation'] ) {
			foreach ( $validation as $valid ) {
				if ( is_wp_error( $valid ) ) {
					$responses[] = $this->envelope_response( $this->error_to_response( $valid ), false )->get_data();
				} else {
					$responses[] = null;
				}
			}

			return new WP_REST_Response(
				array(
					'failed'    => 'validation',
					'responses' => $responses,
				),
				207
			);
		}

		foreach ( $requests as $i => $single_request ) {
			$clean_request = clone $single_request;
			$clean_request->set_url_params( array() );
			$clean_request->set_attributes( array() );
			$clean_request->set_default_params( array() );

			/** This filter is documented in wp-includes/rest-api/class-wp-rest-server.php */
			$result = apply_filters( 'rest_pre_dispatch', null, $this, $clean_request );

			if ( empty( $result ) ) {
				$match = $matches[ $i ];
				$error = null;

				if ( is_wp_error( $validation[ $i ] ) ) {
					$error = $validation[ $i ];
				}

				if ( is_wp_error( $match ) ) {
					$result = $this->error_to_response( $match );
				} else {
					list( $route, $handler ) = $match;

					if ( ! $error && ! is_callable( $handler['callback'] ) ) {
						$error = new WP_Error(
							'rest_invalid_handler',
							__( 'The handler for the route is invalid' ),
							array( 'status' => 500 )
						);
					}

					$result = $this->respond_to_request( $single_request, $route, $handler, $error );
				}
			}

			/** This filter is documented in wp-includes/rest-api/class-wp-rest-server.php */
			$result = apply_filters( 'rest_post_dispatch', rest_ensure_response( $result ), $this, $single_request );

			$responses[] = $this->envelope_response( $result, false )->get_data();
		}

		return new WP_REST_Response( array( 'responses' => $responses ), 207 );
	}

	public function send_headers( $headers ) {
		foreach ( $headers as $key => $value ) {
			$this->send_header( $key, $value );
		}
	}

	public function get_headers( $server ) {
		$headers = array();

		// CONTENT_* headers are not prefixed with HTTP_.
		$additional = array(
			'CONTENT_LENGTH' => true,
			'CONTENT_MD5'    => true,
			'CONTENT_TYPE'   => true,
		);

		foreach ( $server as $key => $value ) {
			if ( str_starts_with( $key, 'HTTP_' ) ) {
				$headers[ substr( $key, 5 ) ] = $value;
			} elseif ( 'REDIRECT_HTTP_AUTHORIZATION' === $key && empty( $server['HTTP_AUTHORIZATION'] ) ) {
				/*
				 * In some server configurations, the authorization header is passed in this alternate location.
				 * Since it would not be passed in in both places we do not check for both headers and resolve.
				 */
				$headers['AUTHORIZATION'] = $value;
			} elseif ( isset( $additional[ $key ] ) ) {
				$headers[ $key ] = $value;
			}
		}

		return $headers;
	}

}
