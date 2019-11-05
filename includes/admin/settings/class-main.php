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
class Main extends Section {
	/**
	 * Stores section settings.
	 *
	 * @var array $settings
	 * @access private
	 */
	private $settings;

	/**
	 * Router constructor.
	 */
	public function __construct() {
		$this->settings = array(
			'wpgraphql_router_access_control_allow_origin' => array(
				'type'        => 'String',
				'description' => __( 'Access-Control-Allow-Origin', 'wp-graphql-cors' ),
				'default'     => '*',
			),
			'wpgraphql_cookie_filter'                      => array(
				'type'        => 'String',
				'description' => __( 'Filter WPGraphQL response cookies', 'wp-graphql-cors' ),
				'default'     => '',
			),
			'wpgraphql_logout_mutation'                    => array(
				'type'        => 'Boolean',
				'description' => __( 'Add logout mutation for destroying user session', 'wp-graphql-cors' ),
				'default'     => false,
			),
			'wpgraphql_endpoint'                           => array(
				'type'        => 'String',
				'description' => __( 'GraphQL endpoint', 'wp-graphql-cors' ),
				'default'     => 'graphql',
			),
		);
		parent::__construct();
	}

	/**
	 * Adds "Router Settings" section to the WPGraphQL Settings page.
	 */
	protected function add_section() {
		add_settings_section(
			'wpgraphql_router_settings_section',
			__( 'General', 'wp-graphql-cors' ),
			array( __CLASS__, 'section_callback' ),
			'wpgraphql_cors'
		);
	}

	/**
	 * Renders section description.
	 */
	protected static function section_callback() {
		esc_html_e( 'Customize GraphQL routing settings', 'wp-graphql-cors' );
	}

	/**
	 * Register all settings.
	 */
	protected function register_settings() {
		foreach ( $this->settings as $name => $args ) {
			register_setting( 'wpgraphql_cors', $name );
		}
	}

	/**
	 * Adds all settings to section.
	 */
	protected function add_settings_fields() {
		foreach ( $this->settings as $name => $args ) {
			if ( is_array( $args ) ) {
				add_settings_field(
					$name,
					! empty( $args['description'] ) ? $args['description'] : '',
					array( __CLASS__, $name ),
					'wpgraphql_cors',
					'wpgraphql_router_settings_section',
					$args
				);
			} else {
				add_settings_field(
					$name,
					$args,
					array( __CLASS__, $name ),
					'wpgraphql_cors',
					'wpgraphql_router_settings_section'
				);
			}
		}
	}
}
