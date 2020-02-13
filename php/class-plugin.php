<?php
/**
 * Bootstraps the Material Theme Builder plugin.
 *
 * @package MaterialThemeBuilder
 */

namespace MaterialThemeBuilder;

use MaterialThemeBuilder\BazBar\Sample;

/**
 * Main plugin bootstrap file.
 */
class Plugin extends Plugin_Base {

	/**
	 * Sample class.
	 *
	 * @var Sample
	 */
	public $sample;

	/**
	 * Initiate the plugin resources.
	 *
	 * Priority is 9 because WP_Customize_Widgets::register_settings() happens at
	 * after_setup_theme priority 10. This is especially important for plugins
	 * that extend the Customizer to ensure resources are available in time.
	 *
	 * @action after_setup_theme, 9
	 */
	public function init() {
		$this->config = apply_filters( 'material_theme_builder_plugin_config', $this->config, $this );

		$this->sample = new Sample( $this );
		$this->sample->init();
	}

	/**
	 * Register necessary assets.
	 * 
	 * @action init
	 */
	public function register_assets() {
		wp_register_style( 
			'google-font', 
			'https://fonts.googleapis.com/css?family=Roboto:300,400,500', 
			[], 
			$this->asset_version() 
		);

		wp_register_style( 
			'material-theme-builder-wp-css', 
			$this->asset_url( 'assets/css/block-editor-compiled.css' ), 
			[], 
			$this->asset_version() 
		);

		wp_register_script( 
			'webcomponents-legacy', 
			$this->asset_url( 'node_modules/@webcomponents/webcomponentsjs/webcomponents-bundle.js' ),
			[],
			$this->asset_version(),
			true
		);

		wp_register_script( 
			'web-components', 
			$this->asset_url( 'assets/js/web-components.js' ),
			[ 'webcomponents-legacy' ],
			$this->asset_version(),
			true
		);
	}

	/**
	 * Enqueue necessary admin assets.
	 * 
	 * @action admin_enqueue_scripts
	 */
	public function load_admin_assets() {
		wp_enqueue_style( 'google-font' );
	}

	/**
	 * Enqueue necessary frontend assets.
	 * 
	 * @action wp_enqueue_scripts
	 */
	public function load_front_assets() {
		wp_enqueue_style( 'google-font' );
		wp_enqueue_style( 'material-theme-builder-wp-css' );
		wp_enqueue_script( 'webcomponents-legacy' );
		wp_enqueue_script( 'web-components' );
	}

	/**
	 * Load Gutenberg assets.
	 *
	 * @action enqueue_block_editor_assets
	 */
	public function enqueue_editor_assets() {
		wp_enqueue_style( 'material-theme-builder-wp-css' );
		wp_enqueue_script( 'webcomponents-legacy' );

		wp_enqueue_script(
			'material-theme-builder-wp-js',
			$this->asset_url( 'assets/js/block-editor.js' ),
			[
				'lodash',
				'react',
				'wp-block-editor',
			],
			$this->asset_version(),
			false
		);
	}

	/**
	 * Register Customizer scripts.
	 *
	 * @action wp_default_scripts, 11
	 *
	 * @param \WP_Scripts $wp_scripts Instance of \WP_Scripts.
	 */
	public function register_scripts( \WP_Scripts $wp_scripts ) {}

	/**
	 * Register Customizer styles.
	 *
	 * @action wp_default_styles, 11
	 *
	 * @param \WP_Styles $wp_styles Instance of \WP_Styles.
	 */
	public function register_styles( \WP_Styles $wp_styles ) {}
}
