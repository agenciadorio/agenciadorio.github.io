<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

add_action( 'admin_init' , '_imagify_create_ngg_table' );
/**
 * Create the Imagify table needed for NGG compatibility.
 *
 * @since 1.5
 * @author Jonathan Buttigieg
 */
function _imagify_create_ngg_table() {
	global $wpdb;

	if ( ! get_option( $wpdb->prefix . 'ngg_imagify_data_db_version' ) ) {
		Imagify_NGG_DB::get_instance()->create_table();
	}
}
