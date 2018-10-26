<?php
if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

$active_plugins = get_option( 'active_plugins', array() );

foreach ( $active_plugins as $key => $active_plugin ) {
	if ( strstr( $active_plugin, '/woocommerce-moip-official.php' ) ) {
		$active_plugins[ $key ] = str_replace( '/woocommerce-moip-official.php', '/woo-moip-official.php', $active_plugin );
	}
}

update_option( 'active_plugins', $active_plugins );