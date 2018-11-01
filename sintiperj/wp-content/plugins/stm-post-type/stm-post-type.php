<?php
/*
Plugin Name: STM Post Type
Plugin URI: http://stylemixthemes.com/
Description: STM Post Type
Author: Stylemix Themes
Author URI: http://stylemixthemes.com/
Text Domain: stm_post_type
Version: 1.0
*/

define( 'STM_POST_TYPE', 'stm_post_type' );
$plugin_path = dirname( __FILE__ );
require_once $plugin_path . '/post_type.class.php';


function stm_plugin_styles() {
	$plugin_url = plugins_url( '', __FILE__ );

	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'stm_plugin_styles' );