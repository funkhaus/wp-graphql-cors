<?php
/**
 * Defines WPGraphQL CORS settings page functions and callbacks.
 *
 * @since 0.0.1
 * @package wp-graphql-cors
 */

/**
 * Registers WPGraphQL CORS settings page to WP Settings menu.
 */
function wpgraphql_cors_admin_menu() {
	add_options_page(
		'WPGraphQL CORS',
		'WPGraphQL CORS',
		'manage_options',
		'wpgraphql-settings',
		'wpgraphql_cors_page'
	);
}

/**
 * Renders WPGraphQL CORS settings page.
 */
function wpgraphql_cors_page() {
	?>
	<form action='options.php' method='post'>
		<h2>WPGraphQL CORS</h2>
		<?php
			settings_fields( 'wpgraphql_cors' );
			do_settings_sections( 'wpgraphql_cors' );
			submit_button();
		?>
	</form>
	<?php
}

/**
 * Initializes the WPGraphQL Settings page.
 */
function wpgraphql_cors_admin_page_init() {
	new \WPGraphQL\CORS\Settings\Access();
	new \WPGraphQL\CORS\Settings\Cookies();
	new \WPGraphQL\CORS\Settings\Extra();
}
