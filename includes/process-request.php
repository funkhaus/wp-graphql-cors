<?php
/**
 * Defines callback functions for manipulating the GraphQL response.
 *
 * @package wp-graphql-cors
 */

/**
 * Remove unneeded cookies before sending graphql response.
 */
function wpgraphql_cors_filter_cookies() {
	$option_value = get_option( 'wpgraphql_cookie_filter', '.*' );
	$expression   = '/' . $option_value . '/';
	$matches      = preg_grep( $expression, array_keys( $_COOKIE ) );

	foreach ( $_COOKIE as $cookie_key => $cookie_value ) {
		if ( in_array( $cookie_key, $matches, true ) ) {
			continue;
		} else {
			unset( $_COOKIE[ $cookie_key ] );
		}
	}
}

/**
 * Filters the GraphQL response headers and sets CORS headers if options is set.
 *
 * @param array $headers WPGraphQL response headers.
 *
 * @return array
 */
function wpgraphql_cors_response_headers( $headers ) {
	$possible_origins = wpgraphql_cors_allowed_origins();

	// Check if current request is in allowed origins.
	if ( wpgraphql_cors_is_allowed_origin() ) {
		$headers['Access-Control-Allow-Origin'] = get_http_origin();
	} else {
		$headers['Access-Control-Allow-Origin'] = $possible_origins[0];
	}
	
	// If current request origin is allowed, allow credentials (cookies).
	if ( $headers['Access-Control-Allow-Origin'] !== '*' && get_option( 'wpgraphql_acac', true ) ) {
		$headers['Access-Control-Allow-Credentials'] = 'true';
	}

	return $headers;
}

/**
 * Filters GraphQL endpoint route.
 *
 * @param string $endpoint WPGraphQL endpoint.
 *
 * @return string
 */
function wpgraphql_cors_graphql_endpoint( $endpoint ) {
	$option_value = get_option( 'wpgraphql_endpoint', 'graphql' );
	if ( '' !== $option_value ) {
		return $option_value;
	}

	return $endpoint;
}

/**
 * Returns array of domain allowed to make request against the WPGraphQL API
 * 
 * @return array
 */
function wpgraphql_cors_allowed_origins() {
	/**
	 * Always add "WordPress Address" to ensure that local POST requests
	 * like those from WPGraphiQL are executed. 
	 */
	$possible_origins = array( get_option( 'siteurl') );

	// If "Use Site Address" option enabled add to possible origins
	if ( ! empty( get_option( 'wpgraphql_acao_use_site_address' ) ) ) {
		$possible_origins[] = get_option( 'home' );
	}
	
	// Add all other possible origins.
	$acao = get_option( 'wpgraphql_acao', '*' );
	if ( $acao !== '*' ) {
		$acao = explode( PHP_EOL, $acao );
		
		$possible_origins = array_merge( $possible_origins, array_map( 'trim', $acao ) );
	}
	
	// Remove empty origins.
	$possible_origins = array_filter( $possible_origins );

	return $possible_origins;
}

/**
 * Checks if the current request domain is allowed to make a request against
 * the WPGraphQL API, if 
 * 
 * @return string|bool
 */
function wpgraphql_cors_is_allowed_origin() {
	$possible_origins = wpgraphql_cors_allowed_origins();

	$request_origin = get_http_origin();

	/**
	 * Bail if no proper "Host" header provided. This is typical of
	 * requests made by tools like Postman.
	 */
	if ( empty( $request_origin ) ) {
		return false;
	}

	// Check each possible origin for a match to the request origin.
	foreach ( $possible_origins as $allowed_origin ) {
		// Strip protocol.
		$haystack = preg_replace( '#^(http(s)?://)#', '', $request_origin );
		$needle   = preg_replace( '#^(http(s)?://)#', '', $allowed_origin );
		$len      = strlen( $needle );

		// If match return true and end loop.
		if ( substr( $haystack, 0, $len ) === $needle ) {
			return true;
		}
	}

	return false;
}

/**
 * Blocks all request from unauthorized domains that aren't introspection query
 * 
 * @param string $query  Request query.
 * 
 * @throws UserError  Unauthorized request
 */
function wpgraphql_cors_api_authentication( $query ) {
	if ( trim( $query ) === trim( $introspection_query ) ) {
		return;
	}

	if ( ! wpgraphql_cors_is_allowed_origin() && ! empty ( get_option( 'wpgraphql_acao_block_unauthorized' ) ) ) {
		throw new \GraphQL\Error\UserError( __( 'You don\'t have the authority to access this API', 'wp-graphql-cors' ) );
	}
}
