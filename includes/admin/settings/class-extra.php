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
class Extra extends Section {
	/**
	 * Holds Section ID
	 * @var string ID
	 */
	const ID = 'wpgraphql_extra_settings_section';

	/**
	 * Holds Section label
	 * @var string LABEL
	 */
    const LABEL = 'Extra';

	/**
	 * Router constructor.
	 */
	public function __construct() {
		$this->settings = array(
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
	 * Renders section description.
	 */
	protected static function section_callback() {
		esc_html_e( 'More features that provide a little more control of the WPGraphQL server', 'wp-graphql-cors' );
	}
}
