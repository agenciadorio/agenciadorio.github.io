<?php
/*
 * this file contains pluing meta information and then shared
 * between pluging and admin classes
 * 
 * [1]
 * TODO: change this meta as plugin needs
 */

$plugin_dir = 'personalize-woocommerce-cart-page';

$plugin_meta		= array('name'			=> 'Personalized WooCommerce Store',
							'shortname'		=> 'nm_woostore',
							'path'			=> WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin_dir,
							'url'			=> plugins_url( $plugin_dir , dirname(__FILE__) ),
							'db_version'	=> 3.0,
							'logo'			=> plugins_url( $plugin_dir.'/images/logo.png' , dirname(__FILE__) ),
							'menu_position'	=> 74);

/*
 * TODO: change the function name
*/
function get_plugin_meta_woostore(){
	
	global $plugin_meta;
	
	//print_r($plugin_meta);
	
	return $plugin_meta;
}