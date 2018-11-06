<?php
/**
 * Plugin Name: Responsive Posts Carousel
 * Plugin URI: http://webcodingplace.com/responsive-posts-carousel-wordpress-plugin
 * Description: The best Posts Slider Plugin for WordPress you will ever need.
 * Version: 4.1
 * Author: WebCodingPlace
 * Author URI: http://webcodingplace.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: responsive-posts-carousel
 */

/*

  Copyright (C) 2018  WebCodingPlace  support@webcodingplace.com
*/

define('RPC_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define('RPC_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define('RPC_VERSION', '4.1' );

require_once('plugin.class.php');
require_once('carousel.class.php');

if( class_exists('WCP_Posts_Carousel')){
	
	$just_initialize = new WCP_Posts_Carousel;
}

if( class_exists('WCP_Responsive_Posts_Carousel')){
	
	$carousel_ob = new WCP_Responsive_Posts_Carousel;
}

?>