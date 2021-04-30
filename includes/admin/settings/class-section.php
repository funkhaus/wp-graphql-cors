<?php
/**
 * The section defines the root functionality for a settings section
 *
 * @package wp-graphql-cors
 */

namespace WPGraphQL\CORS\Settings;

/**
 * Section class
 */
abstract class Section {

	/**
     * Returns Section settings fields.
     *
     * @return array
     */
    public abstract static function get_fields();
}
