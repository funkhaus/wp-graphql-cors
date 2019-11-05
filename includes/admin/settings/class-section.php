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
	 * Section constructor
	 */
	public function __construct() {
		$this->add_section();
		$this->register_settings();
		$this->add_settings_fields();
	}

	/**
	 * Processes calls to setting callback.
	 *
	 * @param string $name      - Name of settings called.
	 * @param array  $arguments - Arguments passed to callback.
	 */
	public static function __callStatic( $name, $arguments = array() ) {
		if ( 'section_callback' === $name ) {
			return static::section_callback();
		}

		return static::render( $name, ...$arguments );
	}

	/**
	 * Renders settings.
	 *
	 * @param string $name - option name of the setting being rendered.
	 * @param array  $args - callback args.
	 */
	protected static function render( $name, $args = array() ) {
		$option_value = get_option(
			$name,
			! empty( $args['default'] ) ? $args['default'] : ''
		);

		$input_type = ! empty( $args['type'] ) ? $args['type'] : 'String';
		switch ( $input_type ) :
			case 'Text':
				?>
					<textarea name='<?php echo esc_html( $name ); ?>'><?php echo esc_textarea( $option_value ); ?></textarea>
				<?php
				break;
			case 'Int':
				?>
					<input type='number' name='<?php echo esc_html( $name ); ?>' value='<?php echo esc_html( $option_value ); ?>'>
				<?php
				break;
			case 'Boolean':
				?>
					<input type='checkbox' name='<?php echo esc_html( $name ); ?>' <?php echo (bool) $option_value ? 'checked' : ''; ?>>
				<?php
				break;
			default:
				?>
					<input type='text' name='<?php echo esc_html( $name ); ?>' value='<?php echo esc_html( $option_value ); ?>'>
				<?php
		endswitch;
	}

	/**
	 * Adds section to the WPGraphQL Settings page.
	 */
	abstract protected function add_section();

	/**
	 * Renders section description.
	 */
	abstract protected static function section_callback();

	/**
	 * Register all settings.
	 */
	abstract protected function register_settings();

	/**
	 * Adds all settings to section.
	 */
	abstract protected function add_settings_fields();
}
