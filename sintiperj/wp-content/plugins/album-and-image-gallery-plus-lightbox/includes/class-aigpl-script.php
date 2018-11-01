<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Aigpl_Script {
	
	function __construct() {
		
		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'aigpl_front_style') );
		
		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'aigpl_front_script') );
		
		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'aigpl_admin_style') );
		
		// Action to add script at admin side
		add_action( 'admin_enqueue_scripts', array($this, 'aigpl_admin_script') );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_front_style() {

		// Registring and enqueing magnific css
		if( !wp_style_is( 'wpos-magnific-style', 'registered' ) ) {
			wp_register_style( 'wpos-magnific-style', AIGPL_URL.'assets/css/magnific-popup.css', array(), AIGPL_VERSION );
			wp_enqueue_style( 'wpos-magnific-style');
		}

		// Registring and enqueing slick css
		if( !wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', AIGPL_URL.'assets/css/slick.css', array(), AIGPL_VERSION );
			wp_enqueue_style( 'wpos-slick-style');	
		}
		
		// Registring and enqueing public css
		wp_register_style( 'aigpl-public-css', AIGPL_URL.'assets/css/aigpl-public.css', null, AIGPL_VERSION );
		wp_enqueue_style( 'aigpl-public-css' );
	}
	
	/**
	 * Function to add script at front side
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_front_script() {

		// Registring magnific popup script
		if( !wp_script_is( 'wpos-magnific-script', 'registered' ) ) {
			wp_register_script( 'wpos-magnific-script', AIGPL_URL.'assets/js/jquery.magnific-popup.min.js', array('jquery'), AIGPL_VERSION, true );
		}
		
		// Registring slick slider script
		if( !wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', AIGPL_URL.'assets/js/slick.min.js', array('jquery'), AIGPL_VERSION, true );
		}

		// Registring public script
		wp_register_script( 'aigpl-public-js', AIGPL_URL.'assets/js/aigpl-public.js', array('jquery'), AIGPL_VERSION, true );
		wp_localize_script( 'aigpl-public-js', 'Aigpl', array(
															'is_mobile' 		=>	(wp_is_mobile()) 	? 1 : 0,
															'is_rtl' 			=>	(is_rtl()) 			? 1 : 0,
														));
	}
	
	/**
	 * Enqueue admin styles
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_admin_style( $hook ) {

		global $typenow;
		
		// If page is plugin setting page then enqueue script
		if( $typenow == AIGPL_POST_TYPE ) {
			
			// Registring admin script
			wp_register_style( 'aigpl-admin-style', AIGPL_URL.'assets/css/aigpl-admin.css', null, AIGPL_VERSION );
			wp_enqueue_style( 'aigpl-admin-style' );
		}
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_admin_script( $hook ) {

		global $wp_version, $wp_query, $typenow;
		
		$new_ui = $wp_version >= '3.5' ? '1' : '0'; // Check wordpress version for older scripts

		if( $typenow == AIGPL_POST_TYPE ) {

			// Enqueue required inbuilt sctipt
			wp_enqueue_script( 'jquery-ui-sortable' );

			// Registring admin script
			wp_register_script( 'aigpl-admin-script', AIGPL_URL.'assets/js/aigpl-admin.js', array('jquery'), AIGPL_VERSION, true );
			wp_localize_script( 'aigpl-admin-script', 'AigplAdmin', array(
																	'new_ui' 				=>	$new_ui,
																	'img_edit_popup_text'	=> __('Edit Image in Popup', 'album-and-image-gallery-plus-lightbox'),
																	'attachment_edit_text'	=> __('Edit Image', 'album-and-image-gallery-plus-lightbox'),
																	'img_delete_text'		=> __('Remove Image', 'album-and-image-gallery-plus-lightbox'),
																));
			wp_enqueue_script( 'aigpl-admin-script' );
			wp_enqueue_media(); // For media uploader
		}
	}
}

$aigpl_script = new Aigpl_Script();