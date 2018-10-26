<?php

/**
 * Submenu page
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class DGWT_WCAS_Admin_Menu {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_menu' ), 20 );
	}

	/**
	 * Add meun items
	 */
	public function add_menu() {

		add_submenu_page( 'woocommerce', __( 'Ajax Search for WooCommerce', 'ajax-search-for-woocommerce' ), __( 'AJAX search form', 'ajax-search-for-woocommerce' ), 'manage_options', 'dgwt_wcas_settings', array( $this, 'settings_page' ));
	}

	/**
	 * Settings page
	 */
	public function settings_page() {
		DGWT_WCAS_Settings::output();
	}

}

$admin_menu = new DGWT_WCAS_Admin_Menu();

