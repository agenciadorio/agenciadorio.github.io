<?php
// We use wooccm_error_log() for reporting to the WooCommerce Logs system
function wooccm_error_log( $message = '' ) {

	if( $message == '' )
		return;

	if( class_exists( 'WC_Logger' ) ) {
		$logger = new WC_Logger();
		$logger->add( 'wooccm', $message );
		return true;
	} else {
		// Fallback where the WooCommerce logging engine is unavailable
		error_log( sprintf( '[checkout-manager] %s', $message ) );
	}

}

function wooccm_get_woo_version() {

	$version = false;
	if( defined( 'WC_VERSION' ) ) {
		$version = WC_VERSION;
	// Backwards compatibility
	} else if( defined( 'WOOCOMMERCE_VERSION' ) ) {
		$version = WOOCOMMERCE_VERSION;
	}
	return $version;

}
?>