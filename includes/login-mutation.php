<?php
/**
 * Define functions for registering the 'logout' mutation to GraphQL Schema.
 *
 * @since 0.0.1
 * @package wp-graphql-cors
 */

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

/**
 * Register logout mutation for killing a user's session and all corresponding cookies.
 */
function wpgraphql_cors_login_mutation() {
	if ( 'on' === get_graphql_setting( 'login_mutation', 'off', 'graphql_cors_settings' ) ) {
		/**
		 * Registers the logout mutation.
		 */
		register_graphql_mutation(
			'loginWithCookies',
			array(
				'inputFields'         => array(
					'login'      => array(
						'type'        => array( 'non_null' => 'String' ),
						'description' => __( 'Input your user/e-mail.', 'wp-graphql-cors' ),
					),
					'password'   => array(
						'type'        => array( 'non_null' => 'String' ),
						'description' => __( 'Input your password.', 'wp-graphql-cors' ),
					),
					'rememberMe' => array(
						'type'        => 'Boolean',
						'description' => __(
							'Whether to "remember" the user. Increases the time that the cookie will be kept. Default false.',
							'wp-graphql-cors'
						),
					),
				),
				'outputFields'        => array(
					'status' => array(
						'type'        => 'String',
						'description' => 'Login operation status',
						'resolve'     => function( $payload ) {
							return $payload['status'];
						},
					),
				),
				'mutateAndGetPayload' => function($input) {
					// Prepare credentials.
					$credential_keys = array(
						'login'      => 'user_login',
						'password'   => 'user_password',
						'rememberMe' => 'remember',
					);
					$credentials     = array();
					foreach ( $input as $key => $value ) {
						if ( in_array( $key, array_keys( $credential_keys ), true ) ) {
							$credentials[ $credential_keys[ $key ] ] = $value;
						}
					}

					// Authenticate User.
					$user = wpgraphql_cors_signon( $credentials );

					if ( is_wp_error( $user ) ) {
						throw new UserError( ! empty( $user->get_error_code() ) ? $user->get_error_code() : 'invalid login' );
					}

					return array( 'status' => 'SUCCESS' );
				},
			)
		);
	}
}
