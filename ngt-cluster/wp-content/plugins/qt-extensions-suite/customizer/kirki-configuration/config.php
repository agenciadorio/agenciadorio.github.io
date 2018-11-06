<?php
/**
 * Kirki Advanced Customizer
 * This is a sample configuration file to demonstrate all fields & capabilities.
 * @package Kirki
 */

/**
 * Documentation: https://github.com/aristath/kirki/wiki
 */

// Early exit if Kirki is not installed
if ( ! class_exists( 'Kirki' ) ) {
	return;
} else {
	include_once( plugin_dir_path( __FILE__ )  . '/sections.php' );
	include_once( plugin_dir_path( __FILE__ )  . '/fields.php' );
	include_once( plugin_dir_path( __FILE__ )  . '/configuration.php' ); 
}


