<?php
/**
 * Defines WPGraphQL CORS settings page functions and callbacks.
 *
 * @since 0.0.1
 * @package wp-graphql-cors
 */

function wpgraphql_cors_settings( \WPGraphQL\Admin\Settings\Settings $manager ) {
	$manager->settings_api->register_section(
		'graphql_cors_settings',
		[ 'title' => __( 'CORS Settings', 'wp-graphql-cors' ) ]
	);

	$manager->settings_api->register_fields(
		'graphql_cors_settings',
		array_merge(
			\WPGraphQL\CORS\Settings\Access::get_fields(),
			\WPGraphQL\CORS\Settings\Cookies::get_fields()
		)
	);
}
