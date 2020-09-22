<?php
/**
 * Class Admin.
 *
 * @package MaterialThemeBuilder
 */

namespace MaterialThemeBuilder;

/**
 * Class to handle admin settings and actions.
 */
class Admin extends Module_Base {

	/**
	 * Create admin pages.
	 * - Getting started page.
	 * - Onboarding Wizard page.
	 *
	 * @action admin_menu
	 *
	 * @return void
	 */
	public function add_pages() {
		add_menu_page(
			esc_html__( 'Material Design for WordPress', 'material-theme-builder' ),
			esc_html__( 'Material', 'material-theme-builder' ),
			'manage_options',
			'material-settings',
			[ $this, 'render_getting_started_page' ],
			trailingslashit( $this->plugin->dir_url ) . 'assets/images/logo-outline.svg'
		);

		add_submenu_page(
			'material-settings',
			esc_html__( 'Getting Started', 'material-theme-builder' ),
			esc_html__( 'Getting Started', 'material-theme-builder' ),
			'manage_options',
			'material-settings',
			[ $this, 'render_getting_started_page' ]
		);

		add_submenu_page(
			'material-settings',
			esc_html__( 'Onboarding Wizard', 'material-theme-builder' ),
			esc_html__( 'Onboarding Wizard', 'material-theme-builder' ),
			'manage_options',
			'material-onboarding-wizard',
			[ $this, 'render_onboarding_wizard_page' ]
		);
	}


	/**
	 * Render getting started page.
	 */
	public function render_getting_started_page() {
		?>
		<div id="material-gsm" class="material-gsm"></div>
		<?php
	}

	/**
	 * Render onboarding wizard page.
	 */
	public function render_onboarding_wizard_page() {
		$this->plugin->customizer_controls->copy_saved_color_settings();
		?>
		<section id="material-onboarding-wizard" class="mdc-typography"></section>
		<?php
	}

	/**
	 * Enqueue getting started assets
	 *
	 * @action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$screen = get_current_screen();

		wp_enqueue_style(
			'material-admin-css',
			$this->plugin->asset_url( 'assets/css/admin-compiled.css' ),
			[],
			$this->plugin->asset_version()
		);

		wp_enqueue_script(
			'material-admin-js',
			$this->plugin->asset_url( 'assets/js/admin.js' ),
			[ 'wp-api-fetch', 'wp-dom-ready' ],
			$this->plugin->asset_version(),
			true
		);

		wp_localize_script(
			'material-admin-js',
			'mtbWizard',
			[
				'restPath'    => esc_url( $this->plugin->onboarding_rest_controller->get_base_path() ),
				'redirect'    => esc_url( admin_url( 'themes.php' ) ),
				'nonce'       => wp_create_nonce( 'wp_rest' ),
				'themeStatus' => esc_html( $this->plugin->theme_status() ),
			]
		);

		if ( $screen && 'toplevel_page_material-settings' === $screen->id ) {
			// Get Roboto Mono and icons.
			wp_enqueue_style(
				'material-admin-google-fonts',
				'//fonts.googleapis.com/css2?family=Roboto+Mono&family=Material+Icons',
				[],
				$this->plugin->asset_version()
			);

			wp_enqueue_style(
				'material-gsm',
				$this->plugin->asset_url( 'assets/css/getting-started-compiled.css' ),
				[ 'material-admin-google-fonts' ],
				$this->plugin->asset_version()
			);

			wp_enqueue_script(
				'material-gsm',
				$this->plugin->asset_url( 'assets/js/getting-started.js' ),
				[ 'wp-element', 'wp-i18n' ],
				$this->plugin->asset_version(),
				true
			);

			$has_demo_content = $this->plugin->importer->has_demo_content();
			wp_localize_script(
				'material-gsm',
				'mtbGsm',
				[
					'wizardUrl'     => esc_url( menu_page_url( 'material-onboarding-wizard', false ) ),
					'editorUrl'     => esc_url( admin_url( 'edit.php' ) ),
					'customize'     => esc_url( admin_url( 'customize.php' ) ),
					'redirect'      => esc_url( admin_url( 'themes.php' ) ),
					'themeStatus'   => esc_html( $this->plugin->theme_status() ),
					'contentStatus' => esc_html( $has_demo_content ? 'ok' : 'install' ),
					'demoBlocks'    => esc_html( $this->plugin->demo_blocks_status() ),
				]
			);

			wp_localize_script(
				'material-admin-js',
				'mtbWizard',
				[
					'restPath'    => esc_url( $this->plugin->onboarding_rest_controller->get_base_path() ),
					'nonce'       => wp_create_nonce( 'wp_rest' ),
					'themeStatus' => esc_html( $this->plugin->theme_status() ),
					'demoBlocks'  => esc_html( $this->plugin->demo_blocks_status() ),
				]
			);
		}

		if ( $screen && 'material_page_material-onboarding-wizard' === $screen->id ) {
			// Get Roboto Mono and icons.
			wp_enqueue_style(
				'material-admin-google-fonts',
				'//fonts.googleapis.com/css2?family=Roboto+Mono&family=Material+Icons',
				[],
				$this->plugin->asset_version()
			);

			wp_enqueue_style(
				'material-wizard',
				$this->plugin->asset_url( 'assets/css/wizard-compiled.css' ),
				[ 'material-admin-google-fonts' ],
				$this->plugin->asset_version()
			);

			wp_enqueue_script(
				'material-wizard',
				$this->plugin->asset_url( 'assets/js/wizard.js' ),
				[ 'wp-element', 'wp-i18n', 'wp-api-fetch' ],
				$this->plugin->asset_version(),
				true
			);

			wp_localize_script(
				'material-wizard',
				'mtbWizard',
				[
					'pagesUrl'         => esc_url( admin_url( 'edit.php?post_type=page' ) ),
					'settingsUrl'      => esc_url( admin_url( 'admin.php?page=material-settings' ) ),
					'assetsPath'       => esc_url( $this->plugin->asset_url( 'assets/images/wizard/' ) ),
					'placeholderImage' => esc_url( $this->plugin->asset_url( 'assets/images/wizard/placeholder.png' ) ),
					'placeholderSmall' => esc_url( $this->plugin->asset_url( 'assets/images/wizard/placeholder-small.png' ) ),
					'nonce'            => wp_create_nonce( 'wp_rest' ),
					'restPath'         => esc_url( $this->plugin->onboarding_rest_controller->get_base_path() ),
					'themeStatus'      => esc_html( $this->plugin->theme_status() ),
				]
			);
		}
	}

	/**
	 * Update theme activated option if material theme is activated.
	 *
	 * @action switch_theme, 10, 2
	 *
	 * @param  string   $new_name Name of the theme.
	 * @param  WP_Theme $new_theme WP_Theme instance of the new theme.
	 * @return void
	 */
	public function switch_theme_material( $new_name, $new_theme ) {
		if ( Plugin::THEME_SLUG === $new_theme->get_stylesheet() ) {
			update_option( 'material_theme_activated', true, false );
		}
	}

	/**
	 * Prints an admin notice.
	 *
	 * @param string $title   The title to be showed in the notice.
	 * @param string $message The message of the notice.
	 *
	 * @return void
	 */
	public function material_notice( $title, $message ) {
		?>

		<div class="notice notice-info is-dismissible material-notice-container">
			<img
				src="<?php echo esc_url( $this->plugin->asset_url( 'assets/images/plugin-logo.png' ) ); ?>"
				alt="<?php esc_attr_e( 'Material Theme Builder', 'material-theme-builder' ); ?>"
			/>

			<div class="material-notice-container__content">
				<h3 class="material-notice-container__content__title">
					<?php echo esc_html( $title ); ?>
				</h3>
				<p class="material-notice-container__content__text">
					<?php
					echo wp_kses(
						$message,
						[
							'a'  => [
								'href'  => [],
								'class' => [],
							],
							'br' => [],
						]
					);
					?>
				</p>
			</div>
		</div>

		<?php
	}

	/**
	 * Show admin notice if theme isn't installed.
	 *
	 * @action admin_notices, 10, 2
	 *
	 * @return void
	 */
	public function theme_not_installed_notice() {
		$status = $this->plugin->theme_status();
		$screen = get_current_screen();

		// Theme already active or inside wizards. Don't show the notice.
		if (
			'ok' === $status
			|| 'toplevel_page_material-settings' === $screen->id
			|| 'material_page_material-onboarding-wizard' === $screen->id
			|| ! empty( get_option( 'material_theme_activated' ) )
		) {
			return;
		}

		$title   = esc_html__(
			'Install Material Theme to take advantage of all Material Plugin customizations',
			'material-theme-builder'
		);
		$message = esc_html__(
			'The Material Plugin enables you to customize Material Components. We recommend installing the companion Material Theme for full site customization.',
			'material-theme-builder'
		);
		$label   = esc_html__( 'Install theme', 'material-theme-builder' );

		if ( 'activate' === $status ) {
			$title   = esc_html__(
				'Activate Material Theme to take advantage of all Material Plugin customizations',
				'material-theme-builder'
			);
			$message = esc_html__(
				'The Material Plugin enables you to customize Material Components. We recommend activating the companion Material Theme for full site customization.',
				'material-theme-builder'
			);
			$label   = esc_html__( 'Activate theme', 'material-theme-builder' );
		}

		$action_link = sprintf(
			'<a href="%s" class="material-theme-%s install-theme">%s</a>',
			esc_url( admin_url( '/themes.php?search=Material+Theme' ) ),
			esc_attr( $status ),
			esc_html( $label )
		);

		$this->material_notice(
			$title,
			sprintf(
				'%s %s',
				$message,
				$action_link
			)
		);
	}

	/**
	 * Show admin notice if theme and plugin are active.
	 *
	 * @action admin_notices, 9, 2
	 *
	 * @return void
	 */
	public function plugin_activated_notice() {
		$screen = get_current_screen();

		// Theme not active or plugin didn't JUST activate. Stop here.
		if ( Plugin::THEME_SLUG !== get_template()
			|| ! get_option( 'mtb_plugin_activated' )
			|| 'toplevel_page_material-settings' === $screen->id
			|| 'material_page_material-onboarding-wizard' === $screen->id ) {
			return;
		}

		delete_option( 'mtb_plugin_activated' );

		$message = esc_html__( "You've set up Material, now it's time to customize your site. Get started by viewing the demo content and entering the Customizer.", 'material-theme-builder' );

		$action_link = sprintf(
			'<br/><a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=material-settings' ) ),
			esc_html__( "Let's go!", 'material-theme-builder' )
		);

		$this->material_notice(
			esc_html__( 'See Material Theming in action', 'material-theme-builder' ),
			sprintf(
				'%s %s',
				$message,
				$action_link
			)
		);
	}
}
