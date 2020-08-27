<?php
/**
 * Section class - CORS
 *
 * @package wp-graphql-cors
 */

namespace WPGraphQL\CORS\Settings;

/**
 * Router class
 */
class Access extends Section {
    /**
	 * Holds Section ID
	 * @var string ID
	 */
    const ID = 'wpgraphql_access_settings_section';
    
    /**
	 * Holds Section label
	 * @var string LABEL
	 */
    const LABEL = 'Access Limitations';

	/**
	 * Router constructor.
	 */
	public function __construct() {
		$this->settings = array(
			'wpgraphql_acao_use_site_address' => array(
				'type'        => 'Boolean',
				'description' => __( 'If checked, the URL set at `Settings > General : Site URL` will be added to the authorized domains header. Extra domains can be added below.', 'wp-graphql-cors' ),
				'label'       => __( 'Add Site Address to "Access-Control-Allow-Origin" header', 'wp-graphql-cors' ),
				'default'     => true,
			),
				'wpgraphql_acao'                  => array(
				'type'              => 'Text',
				'description'       => __( 'Authorized domains requests can originate from. One domain per line. Be sure to include the protocol, like so: http://example.com', 'wp-graphql-cors' ),
				'label'             => __( 'Extend "Access-Control-Allow-Origin” header', 'wp-graphql-cors' ),
				'default'           => '*',
				'sanitize_callback' => 'sanitize_textarea_field',
			),
				'wpgraphql_acah' => array(
				'type'              => 'Text',
				'description'       => __( 'Custom headers fields. One field per line. Like so: X-Custom-Field', 'wp-graphql-cors' ),
				'label'             => __( 'Extend "Access-Control-Allow-Headers”', 'wp-graphql-cors' ),
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			),
				'wpgraphql_acao_block_unauthorized' => array(
				'type'        => 'Boolean',
				'description' => __( 'If checked, all GraphQL requests made from unauthorized domains will be blocked', 'wp-graphql-cors' ),
				'label'       => __( 'Block unauthorized domains', 'wp-graphql-cors' ),
				'default'     => false,
			),
		);
		parent::__construct();
	}

	/**
	 * Renders section description.
	 */
	protected static function section_callback() {
		esc_html_e( 'These settings control access to the GraphQL endpoint based upon requesting domain.', 'wp-graphql-cors' );
	}
}
