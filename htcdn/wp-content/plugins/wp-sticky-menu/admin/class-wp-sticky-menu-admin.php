<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ptheme.com/
 * @since      1.0.0
 *
 * @package    Wp_Sticky_Menu
 * @subpackage Wp_Sticky_Menu/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Sticky_Menu
 * @subpackage Wp_Sticky_Menu/admin
 * @author     PTHEME <support@ptheme.com>
 */
class Wp_Sticky_Menu_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add a custom men location.
	 *
	 * @since    1.0.0
	 */
	public function WPSM_Add_Menu() {
		register_nav_menu( 'wpsm', __( 'WP Sticky Menu', $this->plugin_name ) );
	}

	/**
	 * Register customizer options for our plugin.
	 *
	 * @since    1.0.0
	 */
	public function WPSM_customize_register( $wp_customize ) {

		// Add WP Sticky Menu section.
		$wp_customize->add_section( 'Wp_Sticky_Menu', array(
			'title'           => __( 'WP Sticky Menu', $this->plugin_name ),
			'description'     => __( 'From here you can configure the settings of our WP Sticky Menu.', $this->plugin_name ),
			'priority'        => 130,
		) );

		// Add WP Sticky Menu settings and controls.
		$wp_customize->add_setting( 'wpsm_wrap_width', array(
			'default'           => '1000',
			'type' 				=> 'option',
			'sanitize_callback' => $this->sanitize_number_field,
			'transport' 		=> 'refresh',
		) );
		$wp_customize->add_control( 'wpsm_wrap_width', array(
			'label'       => __( 'Menu Wrap Width (Unit: px)', $this->plugin_name ),
			'description' => __( 'Enter the max-width of the menu wrap. This should depend on your theme layout. Default: 1000', $this->plugin_name ),
			'section'     => 'Wp_Sticky_Menu',
			'type' 		  => 'number',
		) );

		$wp_customize->add_setting( 'wpsm_background', array(
			'default'           => '#ffffff',
			'type' 				=> 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport' 		=> 'postMessage',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpsm_background', array(
			'label'       => __( 'Menu Background Color', $this->plugin_name ),
			'description' => __( 'Pick the background color of the sticky menu. Default: #ffffff', $this->plugin_name ),
			'section'     => 'Wp_Sticky_Menu',
		) ) );

		$wp_customize->add_setting( 'wpsm_background_hover', array(
			'default'           => '#e6e6e6',
			'type' 				=> 'option',
			'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpsm_background_hover', array(
			'label'       => __( 'Menu Hover Background Color', $this->plugin_name ),
			'description' => __( 'Pick the background color of the sticky menu. Default: #e6e6e6', $this->plugin_name ),
			'section'     => 'Wp_Sticky_Menu',
		) ) );

		$wp_customize->add_setting( 'wpsm_font_color', array(
			'default'           => '#1a1a1a',
			'type' 				=> 'option',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport' 		=> 'postMessage',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpsm_font_color', array(
			'label'       => __( 'Menu Font Color', $this->plugin_name ),
			'description' => __( 'Pick the font color of the sticky menu. Default: #1a1a1a', $this->plugin_name ),
			'section'     => 'Wp_Sticky_Menu',
		) ) );

		$wp_customize->add_setting( 'wpsm_font_color_hover', array(
			'default'           => '#222222',
			'type' 				=> 'option',
			'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpsm_font_color_hover', array(
			'label'       => __( 'Menu Font Hover Color', $this->plugin_name ),
			'description' => __( 'Pick the font  hover color of the sticky menu. Default: #222222', $this->plugin_name ),
			'section'     => 'Wp_Sticky_Menu',
		) ) );

		$wp_customize->add_setting( 'wpsm_logo', array(
			'default'           => '',
			'type' 				=> 'option',
			'transport' 		=> 'refresh',
		) );
		$wp_customize->add_control( new WP_Customize_image_Control( $wp_customize, 'wpsm_logo', array(
			'label'       => __( 'Logo Image', $this->plugin_name ),
			'description' => __( 'Upload your logo using the Select Image Button. If no image selected, site title will appear. Recommended Size: 100x50', $this->plugin_name ),
			'section'     => 'Wp_Sticky_Menu',
		) ) );

		$wp_customize->add_setting( 'wpsm_social_btns', array(
			'default'           => '',
			'type' 				=> 'option',
			'transport' 		=> 'refresh',
		) );
		$wp_customize->add_control( 'wpsm_social_btns', array(
		  	'label'    		=> __( 'Enable Social Profiles', $this->plugin_name ),
			'description' 	=> __( 'By enabling it, enter your social profiles in below fields and they will appear on the right of WP Sticky Menu.', $this->plugin_name ),
			'section'  		=> 'Wp_Sticky_Menu',
			'type' 			=> 'checkbox',
		) );

		$wp_customize->add_setting( 'wpsm_facebook', array(
			'default'           => '',
			'type' 				=> 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'wpsm_facebook', array(
		  	'label'    		=> __( 'Facebook Page URL', $this->plugin_name ),
			'description' 	=> __( 'Enter your Facebook URL here.', $this->plugin_name ),
			'section'  		=> 'Wp_Sticky_Menu',
			'type' 			=> 'text',
		) );

		$wp_customize->add_setting( 'wpsm_twitter', array(
			'default'           => '',
			'type' 				=> 'option',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'wpsm_twitter', array(
		  	'label'    		=> __( 'Twitter Page URL', $this->plugin_name ),
			'description' 	=> __( 'Enter your Twitter URL here.', $this->plugin_name ),
			'section'  		=> 'Wp_Sticky_Menu',
			'type' 			=> 'text',
		) );

	}

	/**
	 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
	 *
	 * @since    1.0.0
	 */
	public function WPSM_customize_preview_js() {

		wp_enqueue_script( $this->plugin_name . '-customize-preview', plugin_dir_url( __FILE__ ) . 'js/customize-preview.js', array( 'customize-preview' ), $this->version, false );

	}

	/**
	 * Sanitize Number field.
	 *
	 * @since    1.1.0
	 */

	private function sanitize_number_field( $value ) {
		if ( is_int($value) ) {
			return absint($value);
		} else {
			return intval(1000);
		}
	}

}
