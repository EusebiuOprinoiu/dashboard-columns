<?php
/**
 * Remove all options on uninstall
 *
 * @since   1.0.0
 * @package Dashboard_Columns
 */





/**
 * Prevent unauthorized access.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}





/**
 * Remove plugin options.
 */
if ( is_multisite() ) {
	global $wpdb;

	// phpcs:ignore
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

	if ( $blogs ) {
		foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );

			delete_option( 'dashboard_columns' );
		}
		restore_current_blog();
	}
} else {
	delete_option( 'dashboard_columns' );
}
