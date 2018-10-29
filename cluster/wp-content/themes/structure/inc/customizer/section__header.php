<?php
/**
 * ============================================================================
 * Create sections: Header settings
 * ============================================================================
 */

function register_sections_header_settings( $wp_customize ) {
	$wp_customize->add_section( 'header_settings_section', array(
		'title'       => __( 'Header', 'thememove' ),
		'description' => __( 'In this section you can control all header settings of your site', 'thememove' ),
		'priority'    => 10,
	) );
}

add_action( 'customize_register', 'register_sections_header_settings' );
/**
 * ============================================================================
 * Create controls for section: header settings
 * ============================================================================
 */
function register_controls_for_header_settings_section( $controls ) {

	$section  = 'header_settings_section';
	$priority = 1;

	//Header Presets Settings Group Title
	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'group_title_header_presets',
		'label'     => __( 'Header Presets', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	//Header Presets choose
	$controls[] = array(
		'type'      => 'select',
		'setting'   => 'header_preset',
		'subtitle'  => __( 'Choose a preset setup for your header', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'default'   => header_preset,
		'choices'   => array(
			'header-preset-01' => __( 'Preset 01', 'thememove' ),
			'header-preset-02' => __( 'Preset 02', 'thememove' ),
			'header-preset-03' => __( 'Preset 03', 'thememove' ),
			'header-preset-04' => __( 'Preset 04', 'thememove' ),
			'header-preset-05' => __( 'Preset 05', 'thememove' ),
			'header-preset-06' => __( 'Preset 06', 'thememove' ),
			'header-preset-07' => __( 'Preset 07', 'thememove' ),
			'header-preset-08' => __( 'Preset 08', 'thememove' ),
		),
		'priority'  => $priority ++
	);

	//Header Presets Settings Group Title
	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'group_title_header_general_settings',
		'label'     => __( 'General Settings', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	//Header Top Area
	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'header_top_enable',
		'label'     => __( 'Top Area', 'thememove' ),
		'subtitle'  => __( 'Enabling this option will show header top area', 'thememove' ),
		'section'   => $section,
		'separator' => true,
		'default'   => header_top_enable,
		'priority'  => $priority ++
	);

	//Header Sticky
	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'header_sticky_enable',
		'label'     => __( 'Sticky', 'thememove' ),
		'subtitle'  => __( 'Enabling this option will sticky your header', 'thememove' ),
		'section'   => $section,
		'separator' => true,
		'default'   => header_sticky_enable,
		'priority'  => $priority ++
	);

	//Header Search
	$controls[] = array(
		'type'      => 'checkbox',
		'mode'      => 'toggle',
		'setting'   => 'header_search_enable',
		'label'     => __( 'Search', 'thememove' ),
		'subtitle'  => __( 'Enabling this option will display search button on header', 'thememove' ),
		'section'   => $section,
		'separator' => true,
		'default'   => header_search_enable,
		'priority'  => $priority ++
	);

	//Header Search
	$controls[] = array(
		'type'     => 'checkbox',
		'mode'     => 'toggle',
		'setting'  => 'header_cart_enable',
		'label'    => __( 'Mini Cart', 'thememove' ),
		'subtitle' => __( 'Enabling this option will display mini cart button on header', 'thememove' ),
		'section'  => $section,
		'default'  => header_cart_enable,
		'priority' => $priority ++
	);

	//Normal Logo Settings
	$controls[] = array(
		'type'      => 'group_title',
		'setting'   => 'site_group_title_normal_logo_settings',
		'label'     => __( 'Logo', 'thememove' ),
		'section'   => $section,
		'separator' => false,
		'priority'  => $priority ++
	);

	//Normal Logo Image
	$controls[] = array(
		'type'        => 'image',
		'setting'     => 'normal_logo_image',
		'label'       => __( 'Logo Image - Normal', 'thememove' ),
		'description' => __( 'Choose a default logo image to display', 'thememove' ),
		'section'     => $section,
		'separator'   => true,
		'default'     => normal_logo_image,
		'priority'    => $priority ++
	);

	return $controls;
}

add_filter( 'kirki/controls', 'register_controls_for_header_settings_section' );