<?php
/**
 * ============================================================================
 * Create sections: Post settings
 * ============================================================================
 */
function register_sections_post_settings( $wp_customize ) {
	$wp_customize->add_section( 'post_settings_section', array(
		'title'    => __( 'Post', 'thememove' ),
		'priority' => 16,
	) );
}

add_action( 'customize_register', 'register_sections_post_settings' );
/**
 * ============================================================================
 * Create controls for section: footer settings
 * ============================================================================
 */
function register_controls_for_post_settings_section( $controls ) {

	$section  = 'post_settings_section';
	$priority = 1;

	//Site Layout
	$controls[] = array(
		'type'      => 'radio',
		'mode'      => 'image',
		'setting'   => 'post_layout',
		'label'     => __( 'Layout', 'thememove' ),
		'subtitle'  => __( 'Choose the post layout you want', 'thememove' ),
		'section'   => $section,
		'separator' => true,
		'default'   => post_layout,
		'choices'   => array(
			'full-width'      => THEME_ROOT . '/core/customizer/assets/images/1c.png',
			'content-sidebar' => THEME_ROOT . '/core/customizer/assets/images/2cr.png',
			'sidebar-content' => THEME_ROOT . '/core/customizer/assets/images/2cl.png',
		),
		'priority'  => $priority ++
	);

	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'post_meta_enable',
		'label'     => __( 'Post Author Box', 'thememove' ),
		'subtitle'  => __( 'Enabling this option will display author box at bottom of post', 'thememove' ),
		'section'   => $section,
		'separator' => true,
		'default'   => post_meta_enable,
		'priority'  => $priority ++
	);

	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'post_feature_image_enable',
		'label'     => __( 'Feature Image', 'thememove' ),
		'subtitle'  => __( 'Enabling this option will display big feature image at top of post', 'thememove' ),
		'section'   => $section,
		'separator' => true,
		'default'   => post_feature_image_enable,
		'priority'  => $priority ++
	);

	$controls[] = array(
		'type'      => 'text',
		'setting'   => 'post_heading_text',
		'label'     => __( '"Our Blog" text', 'thememove' ),
		'subtitle'  => __( 'Choose the text for single post heading', 'thememove' ),
		'section'   => $section,
		'separator' => true,
		'default'   => post_heading_text,
		'priority'  => $priority ++
	);

	return $controls;
}

add_filter( 'kirki/controls', 'register_controls_for_post_settings_section' );