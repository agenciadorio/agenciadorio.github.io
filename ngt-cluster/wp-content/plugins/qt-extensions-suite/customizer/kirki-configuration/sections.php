<?php  
/**
 * Create sections using the WordPress Customizer API.
 * @package Kirki
 */
if(!function_exists('qt_kirki_sections')){
function qt_kirki_sections( $wp_customize ) {

	/**
	 * Add sections
	 */
	$wp_customize->add_section( 'qt_maps_section', array(
		'title'       => esc_attr__( 'Google Maps settings', "qthemestd" ),
		'priority'    => 999,
		'description' => esc_attr__( 'Maps api and more', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_advanced_section', array(
		'title'       => esc_attr__( 'Advanced settings', "qthemestd" ),
		'priority'    => 999,
		'description' => esc_attr__( 'Geek stuff for developers', "qthemestd" ),
	));

	
	$wp_customize->add_section( 'qt_colors_section', array(
		'title'       => esc_attr__( 'Colors customization', "qthemestd" ),
		'priority'    => 50,
		'description' => esc_attr__( 'Colors of your website', "qthemestd" ),
	));


	$wp_customize->add_section( 'qt_header_section', array(
		'title'       => esc_attr__( 'Header Customization', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Header settings', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_typography', array(
		'title'       => esc_attr__( 'Typography', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Customize font settings', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_radioplayer_section', array(
		'title'       => esc_attr__( 'Radio settings', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Radio schedule and player settings', "qthemestd" ),
	));



	$wp_customize->add_section( 'qt_footer_section', array(
		'title'       => esc_attr__( 'Footer Customization', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Footer text and functions', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_post_section', array(
		'title'       => esc_attr__( 'Posts layout', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Manage the way posts are displayed', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_release_section', array(
		'title'       => esc_attr__( 'Releases settings', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Manage the way releases are displayed', "qthemestd" ),
	));



	$wp_customize->add_section( 'qt_podcast_section', array(
		'title'       => esc_attr__( 'Podcast settings', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Manage the way podcast are displayed', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_revent_section', array(
		'title'       => esc_attr__( 'Events settings', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Manage the way releases are displayed', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_pagination_section', array(
		'title'       => esc_attr__( 'Pagination layout', "qthemestd" ),
		'priority'    => 100,
		'description' => esc_attr__( 'Pagination options', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_social_section', array(
		'title'       => esc_attr__( 'Social networks', "qthemestd" ),
		'priority'    => 50,
		'description' => esc_attr__( 'Social network profiles', "qthemestd" ),
	));

	$wp_customize->add_section( 'qt_related_section', array(
		'title'       => esc_attr__( 'Related contents', "qthemestd" ),
		'priority'    => 110,
		'description' => esc_attr__( 'Manage the related posts sections', "qthemestd" ),
	));


}}
add_action( 'customize_register', 'qt_kirki_sections' );
