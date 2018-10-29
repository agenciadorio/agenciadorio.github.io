<?php
/**
 * ============================================================================
 * Create sections: custom css/js settings
 * ============================================================================
 */
function register_sections_custom_code( $wp_customize ) {

	//Custom CSS section
	$wp_customize->add_section( 'custom_code_section', array(
		'title'       => __( 'Custom Code', 'thememove' ),
		'description' => __( 'In this section you can add custom JavaScript and CSS to your site.', 'thememove' ),
		'priority'    => 35
	) );

}

add_action( 'customize_register', 'register_sections_custom_code' );

/**
 * ============================================================================
 * Create controls for section: custom css settings
 * ============================================================================
 */
function register_controls_for_custom_code_section( $controls ) {

	$section  = 'custom_code_section';
	$priority = 1;

	//Custom CSS Settings Group Title
	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'site_group_title_custom_css',
		'label'     => __( 'Custom CSS', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	//Enable Custom CSS
	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'custom_css_enable',
		'subtitle'  => __( 'Enabling this option will apply custom css to your site', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'default'   => custom_css_enable,
		'priority'  => $priority ++
	);

	//Custom CSS
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'custom_css',
		'label'       => __( 'Custom CSS', 'thememove' ),
		'section'     => $section,
		'placeholder' => __( 'Entry your custom css code here', 'thememove' ),
		'priority'    => $priority ++
	);

	//Custom Javascript Settings Group Title
	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'site_group_title_custom_js',
		'label'     => __( 'Custom Javascript', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	//Enable Custom Javascript
	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'custom_js_enable',
		'subtitle'  => __( 'Enabling this option will apply custom Javascript to your site', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'default'   => custom_js_enable,
		'priority'  => $priority ++
	);


	//Custom JavaScript
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'custom_js',
		'label'       => __( 'Custom JavaScript', 'thememove' ),
		'section'     => $section,
		'placeholder' => __( 'Entry your custom js code here', 'thememove' ),
		'priority'    => $priority ++
	);

	return $controls;
}

add_filter( 'kirki/controls', 'register_controls_for_custom_code_section' );