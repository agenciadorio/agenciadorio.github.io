<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

global $pagenow;

add_filter( 'heartbeat_received', '_imagify_heartbeat_received', 10, 2 );
/**
 * Prepare the data that goes back with the Heartbeat API.
 *
 * @since 1.4.5
 *
 * @param  array $response The Heartbeat response.
 * @param  array $data     The $_POST data sent.
 * @return array
 */
function _imagify_heartbeat_received( $response, $data ) {
	if ( empty( $data['imagify_heartbeat'] ) || 'update_bulk_data' !== $data['imagify_heartbeat'] ) {
		return $response;
	}

	$saving_data = imagify_count_saving_data();
	$user        = new Imagify_User();

	$response['imagify_bulk_data'] = array(
		'already_optimized_attachments' => number_format_i18n( $saving_data['count'] ),
		'optimized_attachments'         => imagify_count_optimized_attachments(),
		'unoptimized_attachments'       => imagify_count_unoptimized_attachments(),
		'errors_attachments'            => imagify_count_error_attachments(),
		'optimized_attachments_percent' => imagify_percent_optimized_attachments(),
		'optimized_percent'             => $saving_data['percent'],
		'original_human'                => size_format( $saving_data['original_size'], 1 ),
		'optimized_human'               => size_format( $saving_data['optimized_size'], 1 ),
		'unconsumed_quota'              => $user->get_percent_unconsumed_quota(),
	);

	return $response;
}


if ( 'upload.php' === $pagenow && isset( $_GET['page'] ) && 'imagify-bulk-optimization' === $_GET['page'] ) { // WPCS: CSRF ok.
	add_filter( 'heartbeat_settings', '_imagify_heartbeat_settings', IMAGIFY_INT_MAX );
}
/**
 * Update the Heartbeat API settings.
 *
 * @since 1.4.5
 *
 * @param  array $settings Heartbeat API settings.
 * @return array
 */
function _imagify_heartbeat_settings( $settings ) {
	$settings['interval'] = 30;
	return $settings;
}
