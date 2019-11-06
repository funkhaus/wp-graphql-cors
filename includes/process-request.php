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
	$possible_origins = array();

	// Get site address as possible origin.
	if ( ! empty( get_option( 'wpgraphql_acao_use_site_address' ) ) ) {
		$site_address = get_option( 'home' );
		if ( ! empty( $site_address ) ) {
			$possible_origins[] = $site_address;
		}
	}

	// Add all other possible origins.
	$acao = get_option( 'wpgraphql_acao', '*' );
	if ( '*' !== $acao ) {
		$possible_origins = array_merge(
			$possible_origins,
			array_map( 'trim', explode( ',', $acao ) )
		);
	}

	if ( in_array( get_http_origin(), $possible_origins, true ) ) {
		$headers['Access-Control-Allow-Origin'] = get_http_origin();
	}

	if ( ! empty( $headers['Access-Control-Allow-Origin'] ) && '*' !== $headers['Access-Control-Allow-Origin'] ) {
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
