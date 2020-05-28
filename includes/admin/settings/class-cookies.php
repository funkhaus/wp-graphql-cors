<?php
/**
 * Section class - Main
 *
 * @package wp-graphql-cors
 */

namespace WPGraphQL\CORS\Settings;

/**
 * Router class
 */
class Cookies extends Section {
    /**
	 * Holds Section ID
	 * @var string ID
	 */
    const ID = 'wpgraphql_cookies_settings_section';
    
    /**
	 * Holds Section label
	 * @var string LABEL
	 */
    const LABEL = 'Cookies';

	/**
	 * Router constructor.
	 */
	public function __construct() {
		$this->settings = array(
			'wpgraphql_acac'      => array(
				'type'        => 'Boolean',
				'description' => __( 'If checked, `Access-Control-Allow-Credentials` header will be set in the response headers with a value of `true`, along any existing cookies using HTTP Cookie headers', 'wp-graphql-cors' ),
				'label'       => __( 'Send site credentials.', 'wp-graphql-cors' ),
				'default'     => true,
			),
			'wpgraphql_cookie_filter'         => array(
				'type'              => 'String',
				'description'       => __( 'By default this plugin sends all cookies along in a response. You can specify specific cookie names here if you want to limit this. `Send site credentials` must be enabled.', 'wp-graphql-cors' ),
				'label'             => __( 'Filter WP GraphQL response cookies', 'wp-graphql-cors' ),
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'wpgraphql_login_mutation'        => array(
				'type'        => 'Boolean',
				'description' => __( 'This extends WP GraphQL to have a login mutation. This is useful if you want add a login form on your frontend. `Send site credentials` must be enabled.', 'wp-graphql-cors' ),
				'label'       => __( 'Enable login mutation', 'wp-graphql-cors' ),
				'default'     => false,
			),
			'wpgraphql_logout_mutation'       => array(
				'type'        => 'Boolean',
				'description' => __( 'This extends WP GraphQL to have a logout mutation. This is useful if you want add a logout button on your frontend. `Send site credentials` must be enabled.', 'wp-graphql-cors' ),
				'label'       => __( 'Enable logout mutation', 'wp-graphql-cors' ),
				'default'     => false,
			),
		);
		parent::__construct();
	}

	/**
	 * Renders section description.
	 */
	protected static function section_callback() {
		esc_html_e( 'These settings control management of any site information return an the GraphQL request.', 'wp-graphql-cors' );
	}
}
