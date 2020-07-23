<?php
/**
 * Prepare the plugin for translation
 *
 * @since   1.0.0
 * @package Dashboard_Columns
 */





/**
 * Prepare the plugin for translation.
 *
 * Load and define the internationalization files making the plugin ready for
 * translation.
 *
 * @since 1.0.0
 */
class Dashboard_Columns_Textdomain {

	/**
	 * Load plugin text-domain.
	 *
	 * Load the plugin text-domain and define the location of our translation files.
	 * See examples below:
	 *
	 * - Global languages folder: wp-content/languages/plugins/dashboard-columns-en_US.mo
	 * - Local languages folder: wp-content/plugins/dashboard-columns/languages/dashboard-columns-en_US.mo
	 *
	 * If no files are found in the global languages folder the plugin uses the files available in the
	 * local folder.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'locale', get_locale(), DASHBOARD_COLUMNS_NAME );

		// Load textdomain from the global languages folder.
		load_textdomain( DASHBOARD_COLUMNS_NAME, trailingslashit( WP_LANG_DIR ) . 'plugins/' . DASHBOARD_COLUMNS_NAME . '-' . $locale . '.mo' );

		// Load textdomain from the local languages folder.
		load_plugin_textdomain( DASHBOARD_COLUMNS_NAME, false, plugin_basename( DASHBOARD_COLUMNS_DIR_PATH ) . '/languages/' );
	}
}
