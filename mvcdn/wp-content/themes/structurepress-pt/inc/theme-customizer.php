<?php
/**
 * Load the Customizer with some custom extended addons
 *
 * @package StructurePress
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

/**
 * This funtion is only called when the user is actually on the customizer page
 * @param  WP_Customize_Manager $wp_customize
 */
if ( ! function_exists( 'structurepress_customizer' ) ) {
	function structurepress_customizer( $wp_customize ) {
		// add required files
		StructurePressHelpers::load_file( '/inc/customizer/class-customize-base.php' );

		new StructurePress_Customizer_Base( $wp_customize );
	}
	add_action( 'customize_register', 'structurepress_customizer' );
}


/**
 * Takes care for the frontend output from the customizer and nothing else
 */
if ( ! function_exists( 'structurepress_customizer_frontend' ) && ! class_exists( 'StructurePress_Customize_Frontent' ) ) {
	function structurepress_customizer_frontend() {
		StructurePressHelpers::load_file( '/inc/customizer/class-customize-frontend.php' );
		$structurepress_customize_frontent = new StructurePress_Customize_Frontent();
	}
	add_action( 'init', 'structurepress_customizer_frontend' );
}
