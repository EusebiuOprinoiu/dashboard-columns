<?php
/**
 * The core plugin class
 *
 * @since   1.0.0
 * @package Dashboard_Columns
 */





/**
 * The core plugin class.
 *
 * This class is used to load all dependencies, prepare the plugin for translation
 * and register all actions and filters with WordPress.
 *
 * @since 1.0.0
 */
class Dashboard_Columns {

	/**
	 * Execute all hooks.
	 *
	 * Load dependencies, the plugin text-domain and execute all hooks
	 * we previously registered inside the function define_hooks().
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->load_dependencies();
		$this->load_textdomain();
		$this->define_hooks();
	}





	/**
	 * Load required dependencies.
	 *
	 * Load the files required to create our plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_dependencies() {
		require_once DASHBOARD_COLUMNS_DIR_PATH . 'includes/class-dashboard-columns-textdomain.php';
		require_once DASHBOARD_COLUMNS_DIR_PATH . 'includes/general/class-dashboard-columns-admin.php';
		require_once DASHBOARD_COLUMNS_DIR_PATH . 'includes/general/class-dashboard-columns-updates.php';
	}





	/**
	 * Load plugin text-domain.
	 *
	 * Uses the Dashboard_Columns_Textdomain class in order to set the text-domain and define
	 * the location of our translation files.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_textdomain() {
		$textdomain = new Dashboard_Columns_Textdomain();

		add_action( 'after_setup_theme', array( $textdomain, 'load_plugin_textdomain' ) );
	}





	/**
	 * Register hooks with WordPress.
	 *
	 * Create objects from classes and register all hooks with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_hooks() {
		// Create objects from classes.
		$admin   = new Dashboard_Columns_Admin();
		$updates = new Dashboard_columns_Updates();

		// Register admin hooks.
		add_action( 'admin_enqueue_scripts', array( $admin, 'enqueue_styles' ) );
		add_action( 'network_admin_notices', array( $admin, 'onboarding_notice' ) );
		add_action( 'admin_notices', array( $admin, 'onboarding_notice' ) );
		add_action( 'load-index.php', array( $admin, 'add_columns' ) );

		// Register db update hooks.
		add_action( 'plugins_loaded', array( $updates, 'maybe_run_recursive_updates' ) );
		add_action( 'wpmu_new_blog', array( $updates, 'maybe_run_activation_script' ), 10, 6 );
	}
}
