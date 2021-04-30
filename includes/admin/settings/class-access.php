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
     * Returns Access settings fields.
     *
     * @return array
     */
    public static function get_fields() {
        return array(
            array(
                'name'    => 'acao_use_site_address',
                'label'   => __( 'Add Site Address to "Access-Control-Allow-Origin" header', 'wp-graphql-cors' ),
                'desc'    => __( 'If checked, the URL set at `Settings > General : Site URL` will be added to the authorized domains header. Extra domains can be added below.', 'wp-graphql-cors' ),
                'type'    => 'checkbox',
                'default' => 'off',
            ),
            array(
                'name'              => 'acao',
                'type'              => 'textarea',
                'desc'              => __( 'Authorized domains requests can originate from. One domain per line. Be sure to include the protocol, like so: http://example.com', 'wp-graphql-cors' ),
                'label'             => __( 'Extend "Access-Control-Allow-Origin” header', 'wp-graphql-cors' ),
                'default'           => '*',
                'sanitize_callback' => 'sanitize_textarea_field',
            ),
            array(
                'name'              => 'acah',
                'type'              => 'textarea',
                'desc'              => __( 'Custom headers fields. One field per line. Like so: X-Custom-Field', 'wp-graphql-cors' ),
                'label'             => __( 'Extend "Access-Control-Allow-Headers”', 'wp-graphql-cors' ),
                'default'           => '',
                'sanitize_callback' => 'sanitize_textarea_field',
            ),
            array(
                'name'    => 'acao_block_unauthorized',
                'type'    => 'checkbox',
                'desc'    => __( 'If checked, all GraphQL requests made from unauthorized domains will be blocked', 'wp-graphql-cors' ),
                'label'   => __( 'Block unauthorized domains', 'wp-graphql-cors' ),
                'default' => 'off',
            ),
        );
    }
}
