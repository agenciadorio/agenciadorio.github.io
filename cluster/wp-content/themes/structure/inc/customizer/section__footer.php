<?php
/**
 * ============================================================================
 * Create sections: Footer settings
 * ============================================================================
 */
function register_sections_footer_settings( $wp_customize ) {
	$wp_customize->add_section( 'footer_settings_section', array(
		'title'       => __( 'Footer', 'thememove' ),
		'description' => __( 'Scroll to bottom of page to see the change', 'thememove' ),
		'priority'    => 15,
	) );
}

add_action( 'customize_register', 'register_sections_footer_settings' );
/**
 * ============================================================================
 * Create controls for section: footer settings
 * ============================================================================
 */
function register_controls_for_footer_settings_section( $controls ) {

	$section  = 'footer_settings_section';
	$priority = 1;

	//Uncovering Footer
	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'footer_uncovering_enable',
		'label'     => __( 'Uncovering', 'thememove' ),
		'subtitle'  => __( 'Enabling this option will make Footer gradually appear on scroll', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'default'   => footer_uncovering_enable,
		'priority'  => $priority ++
	);

	//Copyright Group Title
	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'site_group_title_footer_copyright',
		'label'     => __( 'Copyright', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	//Copyright
	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'footer_copyright_enable',
		'subtitle'  => __( 'Enabling this option will display copyright info', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'default'   => footer_copyright_enable,
		'priority'  => $priority ++
	);

	//Copyright Text
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'copyright_text',
		'label'       => __( 'Copyright Text', 'thememove' ),
		'section'     => $section,
		'placeholder' => __( 'Entry your custom css code here', 'thememove' ),
		'default'     => __( 'Copyright 2015. All right reserved.', 'Wordpress' ),
		'priority'    => $priority ++
	);

	return $controls;
}

add_filter( 'kirki/controls', 'register_controls_for_footer_settings_section' );