<?php

/**
 * Contact Profile container content element for the Visual Composer editor,
 * that allows nesting of the Contact Detail Item VC content element
 */

if ( ! class_exists( 'PT_VC_Container_Contact_Profile' ) ) {
	class PT_VC_Container_Contact_Profile extends PT_VC_Shortcode {

		// Basic shortcode settings
		function shortcode_name() { return 'pt_vc_container_contact_profile'; }

		// Initialize the shortcode by calling the parent constructor
		public function __construct() {
			parent::__construct();
		}

		// Overwrite the register_shortcode function from the parent class
		public function register_shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'name'         => '',
				'image'        => '',
				'social_icons' => '',
				'new_tab'      => '',
				), $atts );

			// Prepare detail items for the Contact Profile widget
			$items = PT_VC_Helper_Functions::get_child_elements_data( $content );

			// Prepare social icons for the Contact Profile widget
			$lines        = explode( PHP_EOL , $atts['social_icons'] );
			$social_icons = array();

			foreach ( $lines as $line ) {
				$split_line = explode( '|', $line );
				if ( isset( $split_line[1] ) ) {
					$tmp_array  = array(
						'link' => wp_strip_all_tags( $split_line[0] ),
						'icon' => wp_strip_all_tags( $split_line[1] ),
					);
					$social_icons[] = $tmp_array;
				}
			}

			$instance = array(
				'name'         => $atts['name'],
				'image'        => $atts['image'],
				'new_tab'      => $atts['new_tab'],
				'items'        => $items,
				'social_icons' => $social_icons,
			);

			ob_start();
				the_widget( 'PW_Contact_Profile', $instance );
			return ob_get_clean();
		}

		// Overwrite the vc_map_shortcode function from the parent class
		public function vc_map_shortcode() {
			vc_map( array(
				'name'            => _x( 'Contact Profile', 'backend', 'structurepress-pt' ),
				'base'            => $this->shortcode_name(),
				'category'        => _x( 'Content', 'backend', 'structurepress-pt' ),
				'icon'            => get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg',
				'as_parent'       => array( 'only' => 'pt_vc_contact_detail_item' ),
				'content_element' => true,
				'js_view'         => 'VcColumnView',
				'params'          => array(
					array(
						'type'        => 'textfield',
						'heading'     => _x( 'Name', 'backend', 'structurepress-pt' ),
						'param_name'  => 'name',
					),
					array(
						'type'       => 'textfield',
						'heading'    => _x( 'Picture URL', 'backend', 'structurepress-pt' ),
						'param_name' => 'image',
					),
					array(
						'type'        => 'lined_textarea',
						'heading'     => _x( 'Social icons', 'backend', 'structurepress-pt' ),
						'description' => _x( 'Enter values for social links - <em>URL</em>|<em>font awesome icon class name</em>. Divide value sets with linebreak "Enter" (Example: https://www.facebook.com/ProteusThemes|fa-facebook-square).', 'backend', 'structurepress-pt' ),
						'param_name'  => 'social_icons',
						'rows'        => '5',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => _x( 'Open social links in new tab', 'backend', 'structurepress-pt' ),
						'param_name' => 'new_tab',
					),
				)
			) );
		}
	}

	// Initialize the class
	new PT_VC_Container_Contact_Profile;

	// The "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_Pt_Vc_Container_Contact_Profile extends WPBakeryShortCodesContainer {}
	}
}