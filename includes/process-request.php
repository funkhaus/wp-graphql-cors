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
    $option_value = get_graphql_setting( 'cookie_filter', '.*', 'graphql_cors_settings' );
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
    $allowed_origin = wpgraphql_cors_get_allowed_origin($possible_origins);

    if ( $allowed_origin != null ) {
        // Origin found in allowed list. set to the same one that was requested
        $headers['Access-Control-Allow-Origin'] = $allowed_origin;
    } elseif ( 'on' !== get_graphql_setting( 'acao_block_unauthorized', 'off', 'graphql_cors_settings' ) ) {
        // Origin is not allowed but doesn't have to be authorized, return wildcard.
        $headers['Access-Control-Allow-Origin'] = '*';
    } else {
        // Origin not found in allow list and must be authorized. Return first allowed orgin.        
        $headers['Access-Control-Allow-Origin'] = $possible_origins[0];
    }

    // If current request origin is allowed, allow credentials (cookies).
	$acac = 'on' === get_graphql_setting( 'acac', 'off', 'graphql_cors_settings' )
		? true
		: false;
    if ( $headers['Access-Control-Allow-Origin'] !== '*' && $acac ) {
        $headers['Access-Control-Allow-Credentials'] = 'true';
    }

    // If custom headers exist apply them
    $custom_headers = array();
    $acah = get_graphql_setting( 'acah', 'graphql_cors_settings' );
    if ( $acah !== '' ) {
        $acah = explode( PHP_EOL, $acah );
        $custom_headers = array_merge( $custom_headers, array_map( 'trim', $acah ) );

        // Remove any empty origins.
        $custom_headers = array_filter( $custom_headers );
    }
    $access_control_allow_headers = apply_filters(
        'graphql_access_control_allow_headers',
        [
            'Authorization',
            'Content-Type',
        ]
    );
    $headers['Access-Control-Allow-Headers'] = implode( ', ', array_merge($access_control_allow_headers, $custom_headers) );

    return $headers;
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
    if ( 'on' === get_graphql_setting( 'acao_use_site_address', 'off', 'graphql_cors_settings' ) ) {
        $possible_origins[] = get_option( 'home' );
    }

    // Add all other possible origins.
    $acao = get_graphql_setting( 'acao', '*', 'graphql_cors_settings' );
    if ( $acao !== '*' ) {
        $acao = explode( PHP_EOL, $acao );

        $possible_origins = array_merge( $possible_origins, array_map( 'trim', $acao ) );

        // Remove any empty origins.
        $possible_origins = array_filter( $possible_origins );
    }

    return $possible_origins;
}

/**
 * Checks if the current request domain is allowed to make a request against
 * the WPGraphQL API, if allowed, return the origin, otherwise null.
 *
 * @return string|null
 */
function wpgraphql_cors_get_allowed_origin($possible_origins) {

    // Retrieve request origin.
    $request_origin = null;
    if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
        $request_origin = wp_unslash( $_SERVER['HTTP_REFERER'] );
    } elseif ( isset( $_SERVER['HTTP_ORIGIN'] ) ) {
		$request_origin = wp_unslash( $_SERVER['HTTP_ORIGIN'] );
	}

    /**
     * Bail if no proper "Host" header provided. This is typical of
     * requests made by tools like Postman.
     */
    if ( empty( $request_origin ) ) {
        return null;
    }

    // Check each possible origin for a match to the request origin.
    foreach ( $possible_origins as $allowed_origin ) {
        // Strip protocol.
        $haystack = trailingslashit( preg_replace( '#^(http(s)?://)#', '', $request_origin ) );
        $needle   = trailingslashit( preg_replace( '#^(http(s)?://)#', '', $allowed_origin ) );
        $len      = strlen( $needle );

        // If match return true and end loop.
        if ( substr( $haystack, 0, $len ) === $needle ) {
            return $allowed_origin;
        }
    }

    return null;
}

/**
 * Blocks all request from unauthorized domains that aren't introspection query
 *
 * @param string $query  Request query.
 *
 * @throws UserError  Unauthorized request
 */
function wpgraphql_cors_api_authentication( $query ) {
    $possible_origins = wpgraphql_cors_allowed_origins();
    $allowed_origin = wpgraphql_cors_get_allowed_origin($possible_origins);

    if ( $allowed_origin == null && ! 'on' === get_graphql_setting( 'acao_block_unauthorized', 'off', 'graphql_cors_settings' ) ) {
        throw new \GraphQL\Error\UserError( __( 'You don\'t have the authority to access this API', 'wp-graphql-cors' ) );
    }

    return $query;
}

function wpgraphql_cors_shutdown() {
	do_action( 'shutdown' );
}
