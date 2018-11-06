<?php  
/**
 * Configuration for the Kirki Customizer.
 * @package Kirki
 */


Kirki::add_config( 'qt_config', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod'
) );



if(!function_exists('qt_kirki_configuration')){
function qt_kirki_configuration( $config ) {
	return wp_parse_args( array (
		'logo_image'   => get_stylesheet_directory_uri() . '/assets/img/qantumthemes-premium-music-themes-logo-site.png',
		'disable_loader' => true
	), $config );
}}

add_filter( 'kirki/config', 'qt_kirki_configuration' );