<?php
/**
 * ============================================================================
 * Create sections: Typography
 * ============================================================================
 */
function register_sections_typo( $wp_customize ) {
	$wp_customize->add_section( 'site_typography_section', array(
		'title'       => __( 'Typography', 'thememove' ),
		'description' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci earum est, explicabo id illo quae!', 'thememove' ),
		'priority'    => 25,
	) );
}

add_action( 'customize_register', 'register_sections_typo' );
/**
 * ============================================================================
 * Create controls for site typography section
 * ============================================================================
 */
function register_controls_for_site_typography_section( $controls ) {

	$section  = 'site_typography_section';
	$priority = 1;

	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'group_title_site_typography_body_font',
		'label'     => __( 'Body Font', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	$controls[] = array(
		'type'      => 'select',
		'setting'   => 'body_font_family',
		'label'     => __( 'Font Family', 'thememove' ),
		'section'   => $section,
		'default'   => body_font_family,
		'choices'   => Kirki_Fonts::get_font_choices(),
		'separator' => true,
		'output'    => array(
			'element'  => 'body,input, select, textarea, p',
			'property' => 'font-family'
		),
		'priority'  => $priority ++
	);

	$controls[] = array(
		'type'      => 'slider',
		'setting'   => 'body_font_size',
		'label'     => __( 'Font Size', 'thememove' ),
		'section'   => $section,
		'default'   => body_font_size,
		'separator' => false,
		'choices'   => array(
			'min'  => 10,
			'max'  => 60,
			'step' => 1,
		),
		'output'    => array(
			'element'  => 'body,[class*="col-"],.footer .menu li',
			'property' => 'font-size',
			'units'    => 'px',
		),
		'transport' => 'postMessage',
		'priority'  => $priority ++
	);

	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'group_title_site_typography_heading_font',
		'label'     => __( 'Heading Font', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	$controls[] = array(
		'type'      => 'select',
		'setting'   => 'site_heading_font_family',
		'label'     => __( 'Font Family', 'thememove' ),
		'section'   => $section,
		'default'   => site_heading_font_family,
		'separator' => true,
		'choices'   => Kirki_Fonts::get_font_choices(),
		'output'    => array(
			'element'  => 'h1',
			'property' => 'font-family'
		),
		'priority'  => $priority ++
	);

	//H1 Font Size
	$controls[] = array(
		'type'      => 'slider',
		'setting'   => 'site_h1_font_size',
		'label'     => __( 'H1 Font Size', 'thememove' ),
		'section'   => $section,
		'default'   => site_h1_font_size,
		'separator' => true,
		'choices'   => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
		'output'    => array(
			'element'  => 'h1',
			'property' => 'font-size',
			'units'    => 'px',
		),
		'transport' => 'postMessage',
		'priority'  => $priority ++
	);

	//H2 Font Size
	$controls[] = array(
		'type'      => 'slider',
		'setting'   => 'site_h2_font_size',
		'label'     => __( 'H2 Font Size', 'thememove' ),
		'section'   => $section,
		'default'   => site_h2_font_size,
		'separator' => true,
		'choices'   => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
		'output'    => array(
			'element'  => 'h2',
			'property' => 'font-size',
			'units'    => 'px',
		),
		'transport' => 'postMessage',
		'priority'  => $priority ++
	);

	//H3 Font Size
	$controls[] = array(
		'type'      => 'slider',
		'setting'   => 'site_h3_font_size',
		'label'     => __( 'H3 Font Size', 'thememove' ),
		'section'   => $section,
		'default'   => site_h3_font_size,
		'separator' => true,
		'choices'   => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
		'output'    => array(
			'element'  => 'h3',
			'property' => 'font-size',
			'units'    => 'px',
		),
		'transport' => 'postMessage',
		'priority'  => $priority ++
	);

	//H4 Font Size
	$controls[] = array(
		'type'      => 'slider',
		'setting'   => 'site_h4_font_size',
		'label'     => __( 'H4 Font Size', 'thememove' ),
		'section'   => $section,
		'default'   => site_h4_font_size,
		'separator' => true,
		'choices'   => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
		'output'    => array(
			'element'  => 'h4',
			'property' => 'font-size',
			'units'    => 'px',
		),
		'transport' => 'postMessage',
		'priority'  => $priority ++
	);

	//H5 Font Size
	$controls[] = array(
		'type'      => 'slider',
		'setting'   => 'site_h5_font_size',
		'label'     => __( 'H5 Font Size', 'thememove' ),
		'section'   => $section,
		'default'   => site_h5_font_size,
		'separator' => true,
		'choices'   => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
		'output'    => array(
			'element'  => 'h5',
			'property' => 'font-size',
			'units'    => 'px',
		),
		'transport' => 'postMessage',
		'priority'  => $priority ++
	);

	//H6 Font Size
	$controls[] = array(
		'type'      => 'slider',
		'setting'   => 'site_h6_font_size',
		'label'     => __( 'H6 Font Size', 'thememove' ),
		'section'   => $section,
		'default'   => site_h6_font_size,
		'choices'   => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
		'output'    => array(
			'element'  => 'h6',
			'property' => 'font-size',
			'units'    => 'px',
		),
		'transport' => 'postMessage',
		'priority'  => $priority ++
	);

	return $controls;
}

add_filter( 'kirki/controls', 'register_controls_for_site_typography_section' );