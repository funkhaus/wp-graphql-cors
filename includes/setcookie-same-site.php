<?php
/**
 * Alternate setcookie function
 * Wrapper for the native php function setcookie.
 *
 * @since 1.1.1
 * @package wp-graphql-cors
 */

/**
 * Wrapper for setcookie that will add samesite to the cookie.
 *
 * @param string $name The name of the cookie.
 * @param string $value The value of the cookie.
 * @param int    $expires The time the cookie expires.
 * @param string $path The path on the server in which the cookie will be available on.
 * @param string $domain The (sub)domain that the cookie is available to.
 * @param bool   $secure Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client.
 * @param bool   $httponly When TRUE the cookie will be made accessible only through the HTTP protocol.
 * @param string $samesite None, Strict, or Lax. Defaults to None.
 * @return void
 */
function wpgraphql_cors_setcookie_same_site( $name, $value, $expires, $path, $domain, $secure, $httponly, $samesite = 'None' ) {
	if ( PHP_VERSION_ID < 70300 ) {
			setcookie( $name, $value, $expires, "$path; samesite=$samesite", $domain, $secure, $httponly );
	} else {
		setcookie(
			$name,
			$value,
			array(
				'expires'  => $expires,
				'path'     => $path,
				'domain'   => $domain,
				'samesite' => $samesite,
				'secure'   => $secure,
				'httponly' => $httponly,
			)
		);
	}
}
