<?php
/**
 * Tests for Admin class.
 *
 * @package MaterialThemeBuilder
 */

namespace MaterialThemeBuilder;

use WP_UnitTest_Factory;

/**
 * Tests for Admin class.
 */
class Test_Admin extends \WP_UnitTestCase {
	/**
	 * Getting started instance
	 *
	 * @var admin
	 */
	private $admin;

	/**
	 * Plugin instance
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		$this->plugin = get_plugin_instance();
		$this->admin  = new Admin( $this->plugin );
	}

	/**
	 * Test init.
	 *
	 * @see Admin::init()
	 */
	public function test_construct() {
		$this->assertEquals( 10, has_action( 'admin_menu', [ $this->plugin->admin, 'add_pages' ] ) );

		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', [ $this->plugin->admin, 'enqueue_assets' ] ) );

		$this->assertEquals( 10, has_action( 'switch_theme', [ $this->plugin->admin, 'switch_theme_material' ] ) );
		$this->assertEquals( 10, has_action( 'admin_notices', [ $this->plugin->admin, 'theme_not_installed_notice' ] ) );
		$this->assertEquals( 9, has_action( 'admin_notices', [ $this->plugin->admin, 'plugin_activated_notice' ] ) );
	}


	/**
	 * Test for add_pages() method.
	 *
	 * @see Plugin::add_pages()
	 */
	public function test_add_pages() {
		$current_user = get_current_user();
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'administrator' ] ) );

		$this->admin->add_pages();

		$this->assertEquals( admin_url( 'admin.php?page=material-settings' ), menu_page_url( 'material-settings', false ) );
		wp_set_current_user( $current_user );
	}

	/**
	 * Test render getting started page
	 *
	 * @see Admin::render_getting_started_page()
	 */
	public function test_render_getting_started_page() {
		ob_start();
		$this->admin->render_getting_started_page();

		$output = ob_get_clean();

		$this->assertContains( '<div id="material-gsm" class="material-gsm"></div>', $output );
	}

	/**
	 * Test render
	 *
	 * @see Admin::render_onboarding_wizard_page()
	 */
	public function test_render_onboarding_wizard_page() {
		ob_start();
		$this->admin->render_onboarding_wizard_page();

		$output = ob_get_clean();

		$this->assertContains( '<section id="material-onboarding-wizard" class="mdc-typography">', $output );
	}

	/**
	 * Test for enqueue_assets() method.
	 *
	 * @see Admin::enqueue_assets()
	 */
	public function test_enqueue_assets() {
		$this->admin->enqueue_assets();

		$this->assertTrue( wp_style_is( 'material-admin-css', 'enqueued' ) );
		$this->assertTrue( wp_script_is( 'material-admin-js', 'enqueued' ) );
	}

	/**
	 * Test for enqueue_assets() method.
	 *
	 * @see Admin::enqueue_assets()
	 */
	public function test_enqueue_assets_getting_started() {
		$screen = get_current_screen();

		$this->admin->enqueue_assets();
		$this->assertFalse( wp_style_is( 'material-gsm', 'enqueued' ) );
		$this->assertFalse( wp_script_is( 'material-gsm', 'enqueued' ) );

		// Verify getting started page assets.
		$this->admin->add_pages();
		set_current_screen( 'toplevel_page_material-settings' );

		$this->admin->enqueue_assets();
		$this->assertTrue( wp_style_is( 'material-admin-google-fonts', 'enqueued' ) );
		$this->assertTrue( wp_style_is( 'material-gsm', 'enqueued' ) );
		$this->assertTrue( wp_script_is( 'material-gsm', 'enqueued' ) );

		$inline_js = wp_scripts()->get_data( 'material-gsm', 'data' );

		// Assert inline js vars contains theme, content, and demo blocks status.
		$this->assertRegexp( '/themeStatus/', $inline_js );
		$this->assertRegexp( '/contentStatus/', $inline_js );
		$this->assertRegexp( '/demoBlocks/', $inline_js );

		if ( $screen ) {
			set_current_screen( $screen );
		}
	}

	/**
	 * Test for enqueue_assets() method.
	 *
	 * @see Admin::enqueue_assets()
	 */
	public function test_enqueue_assets_onboarding_wizard() {
		$screen = get_current_screen();

		$this->admin->enqueue_assets();
		$this->assertFalse( wp_style_is( 'material-wizard', 'enqueued' ) );
		$this->assertFalse( wp_script_is( 'material-wizard', 'enqueued' ) );

		// Verify getting started page assets.
		$this->admin->add_pages();
		set_current_screen( 'material_page_material-onboarding-wizard' );

		$this->admin->enqueue_assets();
		$this->assertTrue( wp_style_is( 'material-admin-google-fonts', 'enqueued' ) );
		$this->assertTrue( wp_style_is( 'material-wizard', 'enqueued' ) );
		$this->assertTrue( wp_script_is( 'material-wizard', 'enqueued' ) );

		$inline_js = wp_scripts()->get_data( 'material-wizard', 'data' );

		// Assert inline js vars contains restPath and nonce.
		$this->assertRegexp( '/restPath/', $inline_js );
		$this->assertRegexp( '/nonce/', $inline_js );

		set_current_screen( $screen );
	}

	/**
	 * Test for material_notice() method.
	 *
	 * @see Plugin::material_notice()
	 */
	public function test_material_notice() {
		ob_start();
		$notice = $this->admin->material_notice( 'Foo', 'Bar <p>Paragraph</p> <a href="#">Link</a>' );

		$this->assertContains( 'Bar Paragraph <a href="#">Link</a>', ob_get_clean() );
	}

	/**
	 * Test for theme_not_installed_notice() method.
	 *
	 * @see Plugin::theme_not_installed_notice()
	 */
	public function test_theme_not_installed_notice() {
		add_filter( 'template', [ $this, 'template' ] );
		$mock = $this->getMockBuilder( Plugin::class )
			->setMethods(
				[
					'theme_installed',
				]
			)
			->getMock();

		$mock->method( 'theme_installed' )
			->will(
				$this->onConsecutiveCalls( false, true )
			);

		set_current_screen( 'options-general-php' );

		$admin = new Admin( $mock );

		ob_start();
		$admin->theme_not_installed_notice();
		$output = ob_get_clean();

		$this->assertContains( '<div class="notice notice-info is-dismissible material-notice-container">', $output );

		ob_start();
		$admin->theme_not_installed_notice();
		$output = ob_get_clean();

		$this->assertEmpty( $output );
		remove_filter( 'template', [ $this, 'template' ] );
	}

	/**
	 * Test for plugin_activated_notice() method.
	 *
	 * @see Plugin::plugin_activated_notice()
	 */
	public function test_plugin_activated_notice() {
		add_filter( 'template', [ $this, 'template' ] );

		set_current_screen( 'options-general-php' );
		update_option( 'mtb_plugin_activated', true, false );

		ob_start();
		$this->admin->plugin_activated_notice();
		$output = ob_get_clean();

		$this->assertContains( '<div class="notice notice-info is-dismissible material-notice-container">', $output );
	}

	/**
	 * Return the material theme template.
	 *
	 * @return string
	 */
	public function template() {
		return 'material-theme';
	}
}
