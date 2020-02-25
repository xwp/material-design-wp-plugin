<?php
/**
 * Class Controls.
 *
 * @package MaterialThemeBuilder
 */

namespace MaterialThemeBuilder\Customizer;

use MaterialThemeBuilder\Module_Base;
use MaterialThemeBuilder\Customizer\Image_Radio_Control;

/**
 * Class Controls.
 */
class Controls extends Module_Base {

	/**
	 * The slug used as a prefix for all settings and controls.
	 *
	 * @var string
	 */
	public $slug = 'material_theme';

	/**
	 * WP_Customize_Manager object reference.
	 *
	 * @var \WP_Customize_Manager
	 */
	public $wp_customize;

	/**
	 * Register customizer options.
	 *
	 * @action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register( $wp_customize ) {
		$this->wp_customize = $wp_customize;

		// Add the panel.
		$this->add_panel();

		// Add all the customizer sections.
		$this->add_sections();

		// Add all controls in the "Theme" section.
		$this->add_theme_controls();
	}

	/**
	 * Add the base panel.
	 *
	 * @return void
	 */
	public function add_panel() {
		/**
		 * Add "Your Material Theme" custom panel.
		 */
		$this->wp_customize->add_panel(
			$this->slug,
			[
				'priority'    => 10,
				'capability'  => 'edit_theme_options',
				'title'       => esc_html__( 'Material Theme Editor', 'material-theme-builder' ),
				'description' => esc_html__( 'Material Theme Description goes here.', 'material-theme-builder' ),
			]
		);
	}

	/**
	 * Add all our customizer sections.
	 *
	 * @return void
	 */
	public function add_sections() {
		$sections = [
			$this->slug . '_style'      => __( 'Design Style', 'material-theme-builder' ),
			$this->slug . '_colors'     => __( 'Color Palettes', 'material-theme-builder' ),
			$this->slug . '_typography' => __( 'Typography', 'material-theme-builder' ),
			$this->slug . '_corners'    => __( 'Corner Styles', 'material-theme-builder' ),
			$this->slug . '_icons'      => __( 'Icon Collections', 'material-theme-builder' ),
		];

		foreach ( $sections as $id => $label ) {
			$args = [
				'priority'   => 10,
				'capability' => 'edit_theme_options',
				'title'      => esc_html( $label ),
				'panel'      => $this->slug,
				'type'       => 'collapse',
			];

			/**
			 * Filters the customizer section args.
			 *
			 * This allows other plugins/themes to change the customizer section args.
			 *
			 * @param array $args Array of section args.
			 * @param string   $id       ID of the setting.
			 */
			$section = apply_filters( 'mtb_customizer_section_args', $args, $id );

			if ( is_array( $section ) ) {
				$this->wp_customize->add_section(
					$id,
					$section
				);
			} elseif ( $section instanceof \WP_Customize_Section ) {
				$this->wp_customize->add_section( $section );
			}
		}
	}

	/**
	 * Add all controls in the "Theme" section.
	 *
	 * @return void
	 */
	public function add_theme_controls() {
		/**
		 * List of all the control settings in the Theme section.
		 */
		$settings = [
			'example_id' => [
				'default' => 'Some Text',
			],
		];

		$this->add_settings( $settings );

		/**
		* List of all the controls in the Theme section.
		 */
		$controls = [
			// Example control.
			// @todo remove.
			'example_id' => [
				'type'    => 'text',
				'section' => $this->slug . '_style',
				'label'   => __( 'Example Text Field', 'material-theme-builder' ),
			],
		];

		$this->add_controls( $controls );
	}

	/**
	 * Add settings to customizer.
	 *
	 * @param  array $settings Array of settings to add to customizer.
	 * @return void
	 */
	public function add_settings( $settings = [] ) {

		foreach ( $settings as $id => $setting ) {
			if ( is_array( $setting ) ) {
				$defaults = [
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				];

				$setting = array_merge( $defaults, $setting );
			}

			/**
			 * Filters the customizer setting args.
			 *
			 * This allows other plugins/themes to change the customizer controls ards
			 *
			 * @param array   $settings[ $id ] Array of setting args.
			 * @param string   $id       ID of the setting.
			 */
			$setting = apply_filters( 'mtb_customizer_setting_args', $setting, $id );

			if ( is_array( $setting ) ) {
				$this->wp_customize->add_setting(
					$id,
					$setting
				);
			} elseif ( $setting instanceof \WP_Customize_Setting ) {
				$this->wp_customize->add_setting( $setting );
			}
		}
	}

	/**
	 * Add controls to customizer.
	 *
	 * @param  array $controls Array of controls to add to customizer.
	 * @return void
	 */
	public function add_controls( $controls = [] ) {

		foreach ( $controls as $id => $control ) {
			/**
			 * Filters the customizer control args.
			 *
			 * This allows other plugins/themes to change the customizer controls ards
			 *
			 * @param array $control Array of control args.
			 * @param string   $id       ID of the control.
			 */
			$control = apply_filters( 'mtb_customizer_control_args', $control, $id );

			if ( is_array( $control ) ) {
				$this->wp_customize->add_control(
					$id,
					$control
				);
			} elseif ( $control instanceof \WP_Customize_Control ) {
				$this->wp_customize->add_control( $control );
			}
		}
	}

	/**
	 * Enqueue Customizer scripts.
	 *
	 * @action customize_controls_enqueue_scripts
	 */
	public function scripts() {
		wp_enqueue_script(
			'material-theme-builder-customizer-js',
			$this->plugin->asset_url( 'assets/js/customize-controls.js' ),
			[ 'customize-controls', 'jquery' ],
			$this->plugin->asset_version(),
			false
		);

		wp_enqueue_style(
			'material-theme-builder-customizer-css',
			$this->plugin->asset_url( 'assets/css/customizer-compiled.css' ),
			[],
			$this->plugin->asset_version()
		);
	}
}
