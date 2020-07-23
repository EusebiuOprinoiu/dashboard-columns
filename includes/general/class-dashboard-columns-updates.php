<?php
/**
 * Plugin updates and migrations
 *
 * @since   1.0.0
 * @package Dashboard_columns
 */





/**
 * Plugin updates and migrations.
 *
 * This class handles all database changes required after a plugin update.
 * It also makes sure the changes propagate on all sites when using Multisite.
 *
 * @since 1.0.0
 */
class Dashboard_Columns_Updates {

	/**
	 * Migrate and update options on plugin updates.
	 *
	 * Compare the current plugin version with the one stored in the options table
	 * and migrate recursively if needed after a plugin update. The migration code for each
	 * version is stored in individual files and it's triggered only if the 'last-updated-version'
	 * parameter is older than versions where changes have been made.
	 *
	 * @since 1.0.0
	 */
	public function maybe_run_recursive_updates() {
		$dashboard_columns = get_option( 'dashboard_columns' );

		if ( ! isset( $dashboard_columns['plugin-version'] ) ) {
			$dashboard_columns['plugin-version'] = DASHBOARD_COLUMNS_VERSION;
			update_option( 'dashboard_columns', $dashboard_columns );
		}

		if ( ! isset( $dashboard_columns['last-updated-version'] ) ) {
			$dashboard_columns['last-updated-version'] = DASHBOARD_COLUMNS_VERSION;
			update_option( 'dashboard_columns', $dashboard_columns );
		}

		if ( version_compare( DASHBOARD_COLUMNS_VERSION, $dashboard_columns['plugin-version'] ) > 0 ) {
			// Migrate options to version 1.1.0.
			if ( version_compare( $dashboard_columns['last-updated-version'], '1.1.0' ) < 0 ) {
				require_once DASHBOARD_COLUMNS_DIR_PATH . 'includes/general/updates/update-to-version-1.1.0.php';
				$dashboard_columns['last-updated-version'] = '1.1.0';
			}

			/* phpcs:ignore
			// Migrate options to version 1.2.0.
			if ( version_compare( $dashboard_columns['last-updated-version'], '1.2.0' ) < 0 ) {
				require_once DASHBOARD_COLUMNS_DIR_PATH . 'includes/general/updates/update-to-version-1.2.0.php';
				$dashboard_columns['last-updated-version'] = '1.2.0';
			}
			*/



			// Update plugin version.
			$dashboard_columns['plugin-version'] = DASHBOARD_COLUMNS_VERSION;

			// Update plugin options.
			update_option( 'dashboard_columns', $dashboard_columns );
		}
	}





	/**
	 * Run activation script for new sites.
	 *
	 * If we are running WordPress Multisite and our plugin is network activated,
	 * run the activation script every time a new site is created.
	 *
	 * @since 1.0.0
	 * @param int    $blog_id Blog ID of the created blog. Optional.
	 * @param int    $user_id User ID of the user creating the blog. Required.
	 * @param string $domain  Domain used for the new blog. Optional.
	 * @param string $path    Path to the new blog. Optional.
	 * @param int    $site_id Site ID. Only relevant on multi-network installs. Optional.
	 * @param array  $meta    Meta data. Used to set initial site options. Optional.
	 */
	public function maybe_run_activation_script( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( $blog_id ) {
			if ( is_plugin_active_for_network( plugin_basename( DASHBOARD_COLUMNS_MAIN_FILE ) ) ) {
				switch_to_blog( $blog_id );

				require_once DASHBOARD_COLUMNS_DIR_PATH . 'includes/class-dashboard-columns-activator.php';
				Dashboard_Columns_Activator::run_activation_script();

				restore_current_blog();
			}
		}
	}
}
