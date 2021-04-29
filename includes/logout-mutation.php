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
function wpgraphql_cors_logout_mutation() {
	if ( 'on' === get_graphql_setting( 'logout_mutation', 'off', 'graphql_cors_settings' ) ) {
		/**
		 * Registers the logout mutation.
		 */
		register_graphql_mutation(
			'logout',
			array(
				'inputFields'         => array(),
				'outputFields'        => array(
					'status' => array(
						'type'        => 'String',
						'description' => 'Logout operation status',
						'resolve'     => function( $payload ) {
							return $payload['status'];
						},
					),
				),
				'mutateAndGetPayload' => function() {
					// Logout and destroy session.
					wp_logout();

					return array( 'status' => 'SUCCESS' );
				},
			)
		);
	}
}
