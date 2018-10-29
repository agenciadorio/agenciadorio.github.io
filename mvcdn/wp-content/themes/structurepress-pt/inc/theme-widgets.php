<?php
/**
 * Load here all the individual widgets
 *
 * @package StructurePress
 */

// ProteusWidgets init
new ProteusWidgets;

// Require the individual widgets
add_action( 'widgets_init', function () {
	// custom widgets in the theme
	$structurepress_custom_widgets = array(
		'widget-call-to-action',
		'widget-portfolio-grid',
		'widget-contact-profile',
	);

	foreach ( $structurepress_custom_widgets as $file ) {
		StructurePressHelpers::load_file( sprintf( '/inc/widgets/%s.php', $file ) );
	}

	// Relying on composer's autoloader, just provide classes from ProteusWidgets
	register_widget( 'PW_Brochure_Box' );
	register_widget( 'PW_Facebook' );
	register_widget( 'PW_Featured_Page' );
	register_widget( 'PW_Icon_Box' );
	register_widget( 'PW_Latest_News' );
	register_widget( 'PW_Opening_Time' );
	register_widget( 'PW_Skype' );
	register_widget( 'PW_Social_Icons' );
	register_widget( 'PW_Testimonials' );
	register_widget( 'PW_Person_Profile' );
	register_widget( 'PW_Accordion' );
	register_widget( 'PW_Steps' );
	register_widget( 'PW_Number_Counter' );
} );