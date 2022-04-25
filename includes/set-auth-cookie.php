<?php
/**
 * Alternate wp_set_auth_cookie function
 *
 * @since 1.1.1
 * @package wp-graphql-cors
 */

/**
 * Sets the authentication cookies based on user ID.
 * Provides an alternative to wp_set_auth_cookie that supports the samesite cookie attribute.
 *
 * @see wp_set_auth_cookie
 *
 * @param int         $user_id  User ID.
 * @param bool        $remember Whether to remember the user.
 * @param bool|string $secure   Whether the auth cookie should only be sent over HTTPS. Default is an empty
 *                              string which means the value of `is_ssl()` will be used.
 */
function wpgraphql_cors_set_auth_cookie( $user_id, $remember = false, $secure = '' ) {
	if ( $remember ) {
			$expiration = time() + apply_filters( 'auth_cookie_expiration', 14 * DAY_IN_SECONDS, $user_id, $remember );

			$expire = $expiration + ( 12 * HOUR_IN_SECONDS );
	} else {
			$expiration = time() + apply_filters( 'auth_cookie_expiration', 2 * DAY_IN_SECONDS, $user_id, $remember );
			$expire     = 0;
	}

	if ( '' === $secure ) {
			$secure = is_ssl();
	}

	$secure_logged_in_cookie = $secure && 'https' === wp_parse_url( get_option( 'home' ), PHP_URL_SCHEME );

	$secure = apply_filters( 'secure_auth_cookie', $secure, $user_id );

	$secure_logged_in_cookie = apply_filters( 'secure_logged_in_cookie', $secure_logged_in_cookie, $user_id, $secure );

	if ( $secure ) {
			$auth_cookie_name = SECURE_AUTH_COOKIE;
			$scheme           = 'secure_auth';
	} else {
			$auth_cookie_name = AUTH_COOKIE;
			$scheme           = 'auth';
	}

    $manager = WP_Session_Tokens::get_instance( $user_id );
    $token   = $manager->create( $expiration );

	$auth_cookie      = wp_generate_auth_cookie( $user_id, $expiration, $scheme, $token );
	$logged_in_cookie = wp_generate_auth_cookie( $user_id, $expiration, 'logged_in', $token );

	do_action( 'set_auth_cookie', $auth_cookie, $expire, $expiration, $user_id, $scheme, $token );

	do_action( 'set_logged_in_cookie', $logged_in_cookie, $expire, $expiration, $user_id, 'logged_in', $token );

	if ( ! apply_filters( 'send_auth_cookies', true ) ) {
			return;
	}

    $samesite = get_graphql_setting( 'samesite_mode', 'None', 'graphql_cors_settings' );

    $cookie_domain = get_graphql_setting( 'cookie_domain', '', 'graphql_cors_settings' );
    
	wpgraphql_cors_setcookie_same_site( $auth_cookie_name, $auth_cookie, $expire, PLUGINS_COOKIE_PATH, $cookie_domain, $secure, $samesite );
	wpgraphql_cors_setcookie_same_site( $auth_cookie_name, $auth_cookie, $expire, ADMIN_COOKIE_PATH, $cookie_domain, $secure, $samesite );
	wpgraphql_cors_setcookie_same_site( LOGGED_IN_COOKIE, $logged_in_cookie, $expire, COOKIEPATH, $cookie_domain, $secure_logged_in_cookie, $samesite );
	if ( COOKIEPATH !== SITECOOKIEPATH ) {
			wpgraphql_cors_setcookie_same_site( LOGGED_IN_COOKIE, $logged_in_cookie, $expire, SITECOOKIEPATH, $cookie_domain, $secure_logged_in_cookie, $samesite );
	}
}
