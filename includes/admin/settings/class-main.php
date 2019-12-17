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
			'wpgraphql_acao_credentials'      => array(
				'type'        => 'Boolean',
				'description' => __( 'If checked, site credentials including cookies will be passed in WPGraphQL requests', 'wp-graphql-cors' ),
				'label'       => __( 'Send site credentials.', 'wp-graphql-cors' ),
				'default'     => false,
			),
			'wpgraphql_acao_use_site_address' => array(
				'type'        => 'Boolean',
				'description' => __( 'If checked, the current Site URL as set in <pre>Settings > General</pre> will be added to the Access-Control-Allow-Origin header. Extra domains can be added below.', 'wp-graphql-cors' ),
				'label'       => __( 'Add Site Address to "Access-Control-Allow-Origin" header', 'wp-graphql-cors' ),
				'default'     => true,
			),
			'wpgraphql_acao'                  => array(
				'type'              => 'String',
				'description'       => __( 'This can be a comma separated list of domains. Generally you want this to be the domain(s) the GraphQL request is coming from. Be sure to include the protocol, like so: http://example.com', 'wp-graphql-cors' ),
				'label'             => __( 'Extend "Access-Control-Allow-Origin” header', 'wp-graphql-cors' ),
				'default'           => '*',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'wpgraphql_cookie_filter'         => array(
				'type'              => 'String',
				'description'       => __( 'By default this plugin sends all cookies along in a response. You can specify specific cookie names here if you want to limit this. "Send site credentials" must be enabled.', 'wp-graphql-cors' ),
				'label'             => __( 'Filter WP GraphQL response cookies', 'wp-graphql-cors' ),
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'wpgraphql_login_mutation'        => array(
				'type'        => 'Boolean',
				'description' => __( 'This extends WP GraphQL to have a login mutation. "Send site credentials" must be enabled.', 'wp-graphql-cors' ),
				'label'       => __( 'Enable login mutation', 'wp-graphql-cors' ),
				'default'     => false,
			),
			'wpgraphql_logout_mutation'       => array(
				'type'        => 'Boolean',
				'description' => __( 'This extends WP GraphQL to have a logout mutation. This is useful if you want add logout button on your frontend. "Send site credentials" must be enabled.', 'wp-graphql-cors' ),
				'label'       => __( 'Enable logout mutation', 'wp-graphql-cors' ),
				'default'     => false,
			),
			'wpgraphql_endpoint'              => array(
				'type'              => 'String',
				'description'       => __( 'You can change the WP GraphQL endpoint to something else if you wish.', 'wp-graphql-cors' ),
				'label'             => __( 'Change WP GraphQL endpoint', 'wp-graphql-cors' ),
				'default'           => 'graphql',
				'sanitize_callback' => array( $this, 'sanitize_endpoint' ),
			),
		);
		parent::__construct();
	}

	/**
	 * Sanitizes "wpgraphql_endpoint" input.
	 *
	 * @param string $input  Raw input.
	 *
	 * @return string.
	 */
	public function sanitize_endpoint( $input ) {
		return ! empty( $input ) ? sanitize_text_field( $input ) : 'graphql';
	}

	/**
	 * Adds "Router Settings" section to the WPGraphQL Settings page.
	 */
	protected function add_section() {
		add_settings_section(
			'wpgraphql_main_settings_section',
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
			register_setting( 'wpgraphql_cors', $name, $args );
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
					! empty( $args['label'] ) ? $args['label'] : '',
					array( __CLASS__, $name ),
					'wpgraphql_cors',
					'wpgraphql_main_settings_section',
					$args
				);
			} else {
				add_settings_field(
					$name,
					$args,
					array( __CLASS__, $name ),
					'wpgraphql_cors',
					'wpgraphql_main_settings_section'
				);
			}
		}
	}
}
