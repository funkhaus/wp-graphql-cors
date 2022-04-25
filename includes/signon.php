<?php
/**
 * Alternate wp_signon function
 *
 * @since 1.1.1
 * @package wp-graphql-cors
 *
 * phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
 */

/**
 * Authenticates and logs a user in with 'remember' capability.
 * Replacement for wp_signon so that we can drop in a new wp_set_auth_cookie.
 *
 * @see wp_signon
 *
 * @global string $auth_secure_cookie
 *
 * @param array       $credentials   Optional. User info in order to sign on.
 * @param string|bool $secure_cookie Optional. Whether to use secure cookie.
 * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
 */
function wpgraphql_cors_signon( $credentials = array() ) {
	if ( empty( $credentials ) ) {
			$credentials = array();

		if ( ! empty( $_POST['log'] ) ) {
				$credentials['user_login'] = wp_unslash( $_POST['log'] );
		}
		if ( ! empty( $_POST['pwd'] ) ) {
				$credentials['user_password'] = $_POST['pwd'];
		}
		if ( ! empty( $_POST['rememberme'] ) ) {
				$credentials['remember'] = $_POST['rememberme'];
		}
	}

	if ( ! empty( $credentials['remember'] ) ) {
			$credentials['remember'] = true;
	} else {
			$credentials['remember'] = false;
	}

	do_action_ref_array( 'wp_authenticate', array( &$credentials['user_login'], &$credentials['user_password'] ) );

    $secure_cookie = is_ssl();

	$secure_cookie = apply_filters( 'secure_signon_cookie', $secure_cookie, $credentials );

	global $auth_secure_cookie;
	$auth_secure_cookie = $secure_cookie; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	add_filter( 'authenticate', 'wp_authenticate_cookie', 30, 3 );

	$user = wp_authenticate( $credentials['user_login'], $credentials['user_password'] );

	if ( is_wp_error( $user ) ) {
			return $user;
	}

	wpgraphql_cors_set_auth_cookie( $user->ID, $credentials['remember'], $secure_cookie );

	do_action( 'wp_login', $user->user_login, $user );
	return $user;
}
