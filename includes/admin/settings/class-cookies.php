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
     * Returns Cookies settings fields.
     *
     * @return array
     */
    public static function get_fields() {
        return array(
            array(
                'name'    => 'acac',
                'type'    => 'checkbox',
                'desc'    => __( 'If checked, `Access-Control-Allow-Credentials` header will be set in the response headers with a value of `true`, along any existing cookies using HTTP Cookie headers', 'wp-graphql-cors' ),
                'label'   => __( 'Send site credentials.', 'wp-graphql-cors' ),
                'default' => 'off',
            ),
            array(
                'name'              => 'cookie_filter',
                'type'              => 'text',
                'desc'              => __( 'By default this plugin sends all cookies along in a response. You can specify specific cookie names here if you want to limit this. `Send site credentials` must be enabled.', 'wp-graphql-cors' ),
                'label'             => __( 'Filter WP GraphQL response cookies', 'wp-graphql-cors' ),
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            array(
                'name'    => 'login_mutation',
                'type'    => 'checkbox',
                'desc'    => __( 'This extends WP GraphQL to have a login mutation. This is useful if you want add a login form on your frontend. `Send site credentials` must be enabled.', 'wp-graphql-cors' ),
                'label'   => __( 'Enable login mutation', 'wp-graphql-cors' ),
                'default' => 'off',
            ),
            array(
                'name'    => 'logout_mutation',
                'type'    => 'checkbox',
                'desc'    => __( 'This extends WP GraphQL to have a logout mutation. This is useful if you want add a logout button on your frontend. `Send site credentials` must be enabled.', 'wp-graphql-cors' ),
                'label'   => __( 'Enable logout mutation', 'wp-graphql-cors' ),
                'default' => 'off',
            ),
            array(
                'name'    => 'samesite_mode',
                'type'    => 'select',
                'desc'    => __( 'This specifies the samesite mode for the cookie generated when the login mutation is called.', 'wp-graphql-cors' ),
                'label'   => __( 'Samesite cookie mode', 'wp-graphql-cors' ),
                'options' => array(
                    'None'      => 'None',
                    'Lax'       => 'LAX',
                    'Strict'    => 'Strict'
                ),
                'default' => 'None'
            ),
            array(
                'name'              => 'cookie_domain',
                'type'              => 'text',
                'desc'              => __( 'Blank by default. To share across subdomains, use your root domain prefixed with a period (e.g. .mydomain.com).', 'wp-graphql-cors' ),
                'label'             => __( 'Cookie domain', 'wp-graphql-cors' ),
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        );
    }
}
