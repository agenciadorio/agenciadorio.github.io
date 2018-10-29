<?php
/**
 * ============================================================================
 * Create sections import/export settings
 * ============================================================================
 */
function register_section_for_io_section( $wp_customize ) {

	//Import/Export Settings
	$wp_customize->add_section( 'io_section', array(
		'title'    => __( 'Import/Export', 'thememove' ),
		//'description' => __('All themes from ThemeMove share the same theme setting structure so you can export then import settings from one theme to another conveniently without any problem.', 'thememove'),
		'priority' => 15000
	) );
}

add_action( 'customize_register', 'register_section_for_io_section' );