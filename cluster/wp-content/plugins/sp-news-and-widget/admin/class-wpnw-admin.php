<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpnw_Admin {
	
	function __construct() {
		
		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpnw_register_menu'), 12 );
		
		// Init Processes
		add_action( 'admin_init', array($this, 'wpnw_admin_init_process') );

		// Filter to add row action in category table
		add_filter( WPNW_CAT.'_row_actions', array($this, 'wpnw_add_tax_row_data'), 10, 2 );
	}

	/**
	 * Function to add menu
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_register_menu() {

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('Upgrade to PRO - WP News and Scrolling Widgets', 'sp-news-and-widget'), '<span style="color:#2ECC71">'.__('Upgrade to PRO', 'sp-news-and-widget').'</span>', 'manage_options', 'wpnw-premium', array($this, 'wpnw_premium_page') );
		
		// Register plugin hire us page
		add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('Hire Us', 'sp-news-and-widget'), '<span style="color:#2ECC71">'.__('Hire Us', 'sp-news-and-widget').'</span>', 'manage_options', 'wpnw-hireus', array($this, 'wpnw_hireus_page') );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_premium_page() {
		include_once( WPNW_DIR . '/admin/settings/premium.php' );		
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_hireus_page() {		
		include_once( WPNW_DIR . '/admin/settings/hire-us.php' );
	}

	/**
	 * Function to notification transient
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.4.3
	 */
	function wpnw_admin_init_process() {
		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wpnw-plugin-notice' ) {
	    	set_transient( 'wpnw_install_notice', true, 604800 );
	    }
	}

	/**
	 * Function to add category row action
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0
	 */
	function wpnw_add_tax_row_data( $actions, $tag ) {
		return array_merge( array( 'wpnw_id' => "<span style='color:#555'>ID: {$tag->term_id}</span>" ), $actions );
	}

}

$wpnw_Admin = new Wpnw_Admin();