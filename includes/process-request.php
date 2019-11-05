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
	$headers['Access-Control-Allow-Origin'] = get_option( 'wpgraphql_router_access_control_allow_origin', '*' );
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
