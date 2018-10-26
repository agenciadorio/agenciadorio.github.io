<?php

// Create a helper function for easy SDK access.
function dgora_asfw_fs() {
	global $dgora_asfw_fs;

	if ( ! isset( $dgora_asfw_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/lib/start.php';

		$dgora_asfw_fs = fs_dynamic_init( array(
			'id'                  => '700',
			'slug'                => 'ajax-search-for-woocommerce',
			'type'                => 'plugin',
			'public_key'          => 'pk_f4f2a51dbe0aee43de0692db77a3e',
			'is_premium'          => false,
			'has_addons'          => false,
			'has_paid_plans'      => false,
			'menu'                => array(
				'first-path'     => 'admin.php?page=dgwt_wcas_settings',
				'account'        => false,
				'contact'        => false,
				'support'        => false,
			),
		) );
	}

	return $dgora_asfw_fs;
}

// Init Freemius.
dgora_asfw_fs();
// Signal that SDK was initiated.
do_action( 'dgora_asfw_fs_loaded' );