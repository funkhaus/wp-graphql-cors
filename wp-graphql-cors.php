<?php
/**
 * Plugin Name: WPGraphQL CORS
 * Description: Allows user to customize WPGraphQL request CORS Headers
 * and response cookie header.
 * Text Domain: wp-graphql-cors
 * Domain Path: /languages
 * Version: 2.1
 *
 * @category WPGraphQL_Extension
 * @package  wp-graphql-cors
 * @author   Geoff Taylor <geoff@axistaylor.com>, Drew Baker <drew@funkhaus.us>
 * @license  http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @link     https://github.com/kidunot89/wp-graphql-cors
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Setups WPGraphQL CORS constants
 */
function wpgraphql_cors_constants() {
	// Plugin version.
	if ( ! defined( 'WPGRAPHQL_CORS_VERSION' ) ) {
		define( 'WPGRAPHQL_CORS_VERSION', '1.1.1' );
	}
	// Plugin Folder Path.
	if ( ! defined( 'WPGRAPHQL_CORS_PLUGIN_DIR' ) ) {
		define( 'WPGRAPHQL_CORS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}
	// Plugin Folder URL.
	if ( ! defined( 'WPGRAPHQL_CORS_PLUGIN_URL' ) ) {
		define( 'WPGRAPHQL_CORS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}
	// Plugin Root File.
	if ( ! defined( 'WPGRAPHQL_CORS_PLUGIN_FILE' ) ) {
		define( 'WPGRAPHQL_CORS_PLUGIN_FILE', __FILE__ );
	}
	// Whether to autoload the files or not.
	if ( ! defined( 'WPGRAPHQL_CORS_AUTOLOAD' ) ) {
		define( 'WPGRAPHQL_CORS_AUTOLOAD', true );
	}
}

/**
 * Initializes WPGraphQL WooCommerce
 */
function wpgraphql_cors_init() {
	wpgraphql_cors_constants();

	require_once WPGRAPHQL_CORS_PLUGIN_DIR . 'includes/class-wp-graphql-cors.php';
	return new WP_GraphQL_CORS();
}

$wp_graphql_cors = wpgraphql_cors_init();
