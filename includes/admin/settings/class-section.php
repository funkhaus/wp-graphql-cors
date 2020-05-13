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
	 * Holds Section ID
	 * @var string ID
	 */
	const ID = '';

	/**
	 * Holds Section label
	 * @var string LABEL
	 */
	const LABEL = '';

	/**
	 * Stores section settings.
	 *
	 * @var array $settings
	 * @access private
	 */
	protected $settings;
	
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
					<textarea cols="40" name='<?php echo esc_html( $name ); ?>'><?php echo esc_textarea( $option_value ); ?></textarea>
					<p class='description' id='<?php echo esc_html( "{$name}-description" ); ?>'><?php echo esc_html( $args['description'] ); ?></p>
				<?php
				break;
			case 'Int':
				?>
					<input type='number' name='<?php echo esc_html( $name ); ?>' value='<?php echo esc_html( $option_value ); ?>'>
					<p class='description' id='<?php echo esc_html( "{$name}-description" ); ?>'><?php echo esc_html( $args['description'] ); ?></p>
				<?php
				break;
			case 'Boolean':
				?>
					<input type='checkbox' name='<?php echo esc_html( $name ); ?>' <?php echo (bool) $option_value ? 'checked' : ''; ?>>
					<p class='description' id='<?php echo esc_html( "{$name}-description" ); ?>'><?php echo esc_html( $args['description'] ); ?></p>
				<?php
				break;
			default:
				?>
					<input type='text' name='<?php echo esc_html( $name ); ?>' value='<?php echo esc_html( $option_value ); ?>'>
					<p class='description' id='<?php echo esc_html( "{$name}-description" ); ?>'><?php echo esc_html( $args['description'] ); ?></p>
				<?php
		endswitch;
	}

	/**
	 * Adds section to the WPGraphQL Settings page.
	 */
	protected function add_section() {
		add_settings_section(
			static::ID,
			static::LABEL,
			array( get_class( $this ), 'section_callback' ),
			'wpgraphql_cors'
		);
	}

	/**
	 * Renders section description.
	 */
	abstract protected static function section_callback();

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
					static::ID,
					$args
				);
			} else {
				add_settings_field(
					$name,
					$args,
					array( __CLASS__, $name ),
					'wpgraphql_cors',
					static::ID
				);
			}
		}
	}
}
